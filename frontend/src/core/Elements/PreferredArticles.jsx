import React from "react";
import { PreferredItemsList } from "./PreferredItemsList";

const PreferredArticles = ({
  loggedUser,
  preferredAuthors,
  preferredSources,
  maxDisplayedAuthors,
  maxDisplayedSources,
}) => {
  return (
    <div>
      {loggedUser && (
        <>
          <PreferredItemsList
            title="Preferred Authors"
            items={preferredAuthors}
            maxDisplayedItems={maxDisplayedAuthors}
          />
          <PreferredItemsList
            title="Preferred Sources"
            items={preferredSources}
            maxDisplayedItems={maxDisplayedSources}
          />
        </>
      )}
    </div>
  );
};

export default PreferredArticles;
