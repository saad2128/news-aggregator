import React from "react";
import { formatArticleDate } from "./Utils";

const placeholderImage =
  "https://via.placeholder.com/1280x720.png?text=No+preview+is+available";

export function ArticleCard({ article, isFirst }) {
  const imageClassName = isFirst
    ? "h-80 w-120 object-cover"
    : "h-48 w-full object-cover";
  const cardClassName = isFirst
    ? "flex flex-col justify-between bg-white p-6"
    : "flex flex-1 flex-col justify-between bg-white p-6";
  return (
    <div className="flex flex-col overflow-hidden rounded-lg shadow-lg">
      <div className={`${isFirst ? "flex flex-col sm:flex-row" : ""}`}>
        <div className="flex-shrink-0">
          <img
            className={imageClassName}
            src={article.url_to_image ? article.url_to_image : placeholderImage}
            alt=""
          />
        </div>
        <div className={cardClassName}>
          <div>
            <p className="text-sm font-medium">
              <span className="text-gray-500">{article.apiSource} | </span>
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
    </div>
  );
}
