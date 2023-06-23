import React from 'react';

const PreferredArticles = ({ loggedUser, preferredAuthors, preferredSources, maxDisplayedAuthors, maxDisplayedSources }) => {
  return (
    <div>
      {/* Conditionally render preferred authors if user is authenticated */}
      {loggedUser && (
        <div className="mt-2">
          <label className="block text-sm font-medium text-gray-700">
            Preferred Authors:
          </label>
          <div className="flex gap-2">
            {preferredAuthors
              .slice(0, maxDisplayedAuthors)
              .map((author) => (
                <div
                  key={author.label}
                  className="px-3 py-1 bg-gray-200 rounded"
                >
                  {author.label}
                </div>
              ))}
            {preferredAuthors.length > maxDisplayedAuthors && (
              <div className="px-3 py-1 ">.. and more</div>
            )}
          </div>
        </div>
      )}

      {/* Conditionally render preferred sources if user is authenticated */}
      {loggedUser && (
        <div className="mt-2">
          <label className="block text-sm font-medium text-gray-700">
            Preferred Sources:
          </label>
          <div className="flex gap-2">
            {preferredSources
              .slice(0, maxDisplayedSources)
              .map((source) => (
                <div
                  key={source.label}
                  className="px-3 py-1 bg-gray-200 rounded"
                >
                  {source.label}
                </div>
              ))}
            {preferredSources.length > maxDisplayedSources && (
              <div className="px-3 py-1  ">.. and more</div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default PreferredArticles;