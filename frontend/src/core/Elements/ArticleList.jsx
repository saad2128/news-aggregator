import React from "react";
import { format, isToday, differenceInHours } from "date-fns";
import { CheckCircleIcon } from "@heroicons/react/24/outline";
import { Link } from "react-router-dom";
import LoadingItem from "./LoadingItem";
import { formatArticleDate } from "./Utils";

const placeholderImage =
  "https://via.placeholder.com/1280x720.png?text=No+preview+is+available";

const ArticleList = ({ articles, loading, loggedUser, totalNews }) => (
  <>
    <p className="mt-4 flex justify-between text-gray-500 text-sm">
      <span className="font-bold text-3xl tracking-tight">Latest News</span>
      <span>
        {totalNews > 0 ? (
          <>
            {`Total ${totalNews} news found`}
            {totalNews > 1 ? "s" : ""}
          </>
        ) : (
          "No news found in this search criteria"
        )}
      </span>
    </p>
    {articles.length ? (
      <div className="mx-auto mt-12 grid max-w-lg gap-5 lg:max-w-none">
        {articles.length > 0 && (
          <div
            key={articles[0].id}
            className="flex flex-col overflow-hidden rounded-lg shadow-lg"
          >
            <div className="flex flex-col sm:flex-row">
              <div className="flex-shrink-0">
                <img
                  className="h-80 w-120 object-cover"
                  src={
                    articles[0].url_to_image
                      ? articles[0].url_to_image
                      : placeholderImage
                  }
                  alt=""
                />
              </div>
              <div className="flex flex-col justify-between bg-white p-6">
                <div>
                  <p className="text-sm font-medium">
                    <span className="text-gray-500">
                      {articles[0].apiSource} |{" "}
                    </span>
                    {articles[0].source?.id && (
                      <a
                        href={articles[0].url}
                        className="text-indigo-600 hover:underline"
                        target="_blank"
                      >
                        {articles[0].source.source}
                      </a>
                    )}
                  </p>
                  <div className="mt-6">
                    <div className="flex space-x-1 text-sm text-gray-500">
                      <span>{formatArticleDate(articles[0].published_at)}</span>
                    </div>
                  </div>
                  <a
                    href={articles[0].url}
                    className="mt-2 block"
                    target="_blank"
                  >
                    <p className="text-xl font-semibold text-gray-900">
                      {articles[0].title}
                    </p>
                    <p className="mt-3 text-base text-gray-500">
                      {articles[0].description}
                    </p>
                  </a>
                </div>
                <div className="mt-6">
                  <p className="text-sm font-medium text-gray-900">
                    {articles[0].raw_author}
                  </p>
                </div>
              </div>
            </div>
          </div>
        )}

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-5">
          {articles.slice(1).map((article) => (
            <div
              key={article.id}
              className="flex flex-col overflow-hidden rounded-lg shadow-lg"
            >
              <div className="flex-shrink-0">
                <img
                  className="h-48 w-full object-cover"
                  src={
                    article.url_to_image
                      ? article.url_to_image
                      : placeholderImage
                  }
                  alt=""
                />
              </div>
              <div className="flex flex-1 flex-col justify-between bg-white p-6">
                <div className="flex-1">
                  <p className="text-sm font-medium">
                    <span className="text-gray-500">
                      {article.apiSource} |{" "}
                    </span>
                    {article.source?.id && (
                      <a
                        href={article.url}
                        className="text-indigo-600 hover:underline"
                        target="_blank"
                      >
                        {article.source.source}
                      </a>
                    )}
                  </p>
                  <div className="mt-6">
                    <div className="flex space-x-1 text-sm text-gray-500">
                      <span>{formatArticleDate(article.published_at)}</span>
                    </div>
                  </div>
                  <a href={article.url} className="mt-2 block" target="_blank">
                    <p className="text-xl font-semibold text-gray-900">
                      {article.title}
                    </p>
                    <p className="mt-3 text-base text-gray-500">
                      {article.description}
                    </p>
                  </a>
                </div>
                <div className="mt-6">
                  <p className="text-sm font-medium text-gray-900">
                    {article.raw_author}
                  </p>
                </div>
              </div>
            </div>
          ))}
        </div>

        {loading && <LoadingItem />}
      </div>
    ) : (
      <>
        {loading ? (
          <div className="mx-auto mt-12 grid max-w-lg gap-5 lg:max-w-none lg:grid-cols-3">
            <LoadingItem />
          </div>
        ) : (
          <div className="text-center py-16 px-6 sm:py-24 lg:px-8">
            <CheckCircleIcon
              className="mx-auto block h-16 w-16 text-center"
              aria-hidden="true"
            />

            <p className="mt-1 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl mb-4">
              No articles available at this moment
            </p>

            <Link
              to={"/"}
              className="text-base font-medium text-indigo-700 hover:text-indigo-600"
            >
              Reload to see new articles <span aria-hidden="true"> →</span>
            </Link>
            {loggedUser && (
              <p className="mt-4 text-sm text-gray-500">
                You can also try by resetting your{" "}
                <Link to={"preferences"} className="text-indigo-700">
                  preference
                </Link>
              </p>
            )}
          </div>
        )}
      </>
    )}
  </>
);

export default ArticleList;
