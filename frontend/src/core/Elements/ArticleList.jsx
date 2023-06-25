import React from "react";
import { Link } from "react-router-dom";
import LoadingItem from "./LoadingItem";
import { ArticleCard } from "./ArticleCard";

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
          <ArticleCard
            article={articles[0]}
            key={articles[0].id}
            isFirst={true}
          />
        )}

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-5">
          {articles.slice(1).map((article) => (
            <ArticleCard article={article} key={article.id} isFirst={false} />
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
            <p className="mt-1 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl mb-4">
              No articles available at this moment
            </p>

            <div className="text-base font-medium text-indigo-700 hover:text-indigo-600">
              Click on Get News to see new articles{" "}
              <span aria-hidden="true"> →</span>
            </div>
            {loggedUser && (
              <p className="mt-4 text-sm text-gray-500">
                You can also consider resetting your{" "}
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
