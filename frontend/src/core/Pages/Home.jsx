import React, { useState, useEffect } from "react";
import { sendGetRequest } from "../ApiService";
import { currentUser } from "../Elements/Utils";
import { AsyncPaginate } from "react-select-async-paginate";
import {
  authorLoadOptions,
  sourceLoadOptions,
  loadSelectedOptions,
} from "../Elements/Utils";
import ArticleList from "../Elements/ArticleList";
import { Link } from "react-router-dom";
import PreferredArticles from "../Elements/PreferredArticles";
import customStyles from "../../styles/customStyles";

const Home = () => {
  const [articles, setArticles] = useState([]);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(true);
  const [loading, setLoading] = useState(true);
  const loggedUser = currentUser();
  const [searchTerm, setSearchTerm] = useState("");
  const [authors, setAuthors] = useState([]);
  const [sources, setSources] = useState([]);
  const [totalNews, setTotalNews] = useState(0);
  const [preferredAuthors, setPreferredAuthors] = useState([]);
  const [preferredSources, setPreferredSources] = useState([]);
  const [isDataLoading, setIsDataLoading] = useState(false);
  const maxDisplayedSources = 5;
  const maxDisplayedAuthors = 5;

  useEffect(() => {
    setLoading(true);

    const authorIds = authors.map((item) => item.value);
    const sourceIds = sources.map((item) => item.value);

    sendGetRequest(
      `articles?page=${page}&search=${searchTerm}&authors=${authorIds}&sources=${sourceIds}`,
      { Authorization: `Bearer ${loggedUser?.token}` }
    ).then(function (response) {
      if (response.status) {
        setTotalNews(response.results?.total);
        if (response.results.data?.length) {
          setArticles(articles.concat(response.results.data));
        } else {
          setHasMore(false);
        }
      }

      setLoading(false);
    });

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, [page, searchTerm, authors, sources]);

  useEffect(() => {
    loadSelectedOptions(
      "authors",
      "author_name",
      setPreferredAuthors,
      loggedUser
    );
    loadSelectedOptions("sources", "source", setPreferredSources, loggedUser);
  }, []);

  function handleScroll() {
    if (!hasMore) return;

    let currentPosition =
      window.innerHeight + document.documentElement.scrollTop;
    if (currentPosition % 1 === 0.5) {
      currentPosition += 0.5;
    }

    if (currentPosition !== document.documentElement.offsetHeight) return;
    setPage(page + 1);
  }

  const onChangeFilter = (value, type) => {
    setHasMore(true);
    setPage(1);
    setArticles([]);

    switch (type) {
      case "search":
        setSearchTerm(value);
        break;
      case "authors":
        setAuthors(value);
        break;
      case "sources":
        setSources(value);
        break;
    }
  };

  let searchDelay = null;
  const searchNewsHandler = (e) => {
    if (searchDelay) {
      clearTimeout(searchDelay);
    }

    searchDelay = setTimeout(() => {
      onChangeFilter(e.target.value, "search");
    }, 1000);
  };

  const handleButtonClick = async () => {
    setIsDataLoading(true); // Set loading state to true

    sendGetRequest(`news`).then(function (response) {
      setIsDataLoading(false);
      window.location.reload();
    });
  };

  return (
    <div className="px-6 pt-1 pb-6 lg:px-8 lg:pt-4 lg:pb-10">
      <div className="mx-auto max-w-7xl px-6 lg:px-8">
        <div className="mx-auto max-w-2xl sm:text-center">
          <h2 className="text-base font-semibold leading-7 text-customBlue">
            Stay Informed, Stay Ahead
          </h2>
          <p className="mt-2 text-3xl font-bold tracking-tight  sm:text-4xl">
            Your Source for Timely News
          </p>
        </div>
      </div>
      <div className="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-8">
        <div className="col-span-1">
          <div className="mt-1">
            <input
              type="text"
              className="block w-full rounded-md border-gray-300 py-2 px-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Search news..."
              onChange={searchNewsHandler}
            />
          </div>
        </div>

        <div className="col-span-1">
          <div className="mt-1">
            <AsyncPaginate
              value={authors}
              loadOptions={(search, loadedOptions, { page }) =>
                authorLoadOptions(search, loadedOptions, { page, loggedUser })
              }
              onChange={(selectedValue) => {
                onChangeFilter(selectedValue, "authors");
              }}
              additional={{
                page: 1,
              }}
              isMulti={true}
              styles={customStyles}
              placeholder={<div>Select author</div>}
            />
          </div>
        </div>

        <div className="col-span-1">
          <div className="mt-1">
            <AsyncPaginate
              value={sources}
              sourceLoadOptions
              loadOptions={(search, loadedOptions, { page }) =>
                sourceLoadOptions(search, loadedOptions, { page, loggedUser })
              }
              onChange={(selectedValue) => {
                onChangeFilter(selectedValue, "sources");
              }}
              additional={{
                page: 1,
              }}
              isMulti={true}
              styles={customStyles}
              placeholder={<div>Select source</div>}
            />
          </div>
        </div>

        {loggedUser && (
          <div className="col-span-1">
            <div className="mt-1">
              <Link
                to="/preferences"
                className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Preferences
              </Link>
            </div>
          </div>
        )}
        <div className="col-span-1">
          <div className="mt-1">
            <button
              onClick={handleButtonClick}
              disabled={isDataLoading} // Disable the button while loading
              className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              {isDataLoading ? "Loading..." : "Get News"}
            </button>
          </div>
        </div>
      </div>

      <div>
        <PreferredArticles
          loggedUser={loggedUser}
          preferredAuthors={preferredAuthors}
          preferredSources={preferredSources}
          maxDisplayedAuthors={maxDisplayedAuthors}
          maxDisplayedSources={maxDisplayedSources}
        />
      </div>

      <ArticleList
        articles={articles}
        loading={loading}
        loggedUser={loggedUser}
        totalNews={totalNews}
      />
    </div>
  );
};

export default Home;
