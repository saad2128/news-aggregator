// authorUtils.js

import { sendGetRequest } from "../ApiService";
import { differenceInHours, format, isToday } from "date-fns";

export async function authorLoadOptions(
  search,
  loadedOptions,
  { page, loggedUser }
) {
  const responseJSON = await sendGetRequest(
    `authors?search=${search}&page=${page}`,
    { Authorization: `Bearer ${loggedUser?.token}` }
  );

  const options = [];
  responseJSON?.results?.data?.map((resultItem) => {
    options.push({ value: resultItem.id, label: resultItem.author_name });
  });

  return {
    options: options,
    hasMore: !(
      responseJSON?.results?.data?.length < responseJSON?.results?.per_page
    ),
    additional: {
      page: page + 1,
    },
  };
}

export async function sourceLoadOptions(
  search,
  loadedOptions,
  { page, loggedUser }
) {
  const responseJSON = await sendGetRequest(
    `sources?search=${search}&page=${page}`,
    { Authorization: `Bearer ${loggedUser?.token}` }
  );

  const options = [];
  responseJSON?.results?.data?.map((resultItem) => {
    options.push({ value: resultItem.id, label: resultItem.source });
  });

  return {
    options: options,
    hasMore: !(
      responseJSON?.results?.data?.length < responseJSON?.results?.per_page
    ),
    additional: {
      page: page + 1,
    },
  };
}

export const loadSelectedOptions = async (
  url,
  optionLabel,
  setSelectedOptions,
  loggedUser
) => {
  try {
    const responseJSON = await sendGetRequest(url, {
      Authorization: `Bearer ${loggedUser?.token}`,
    });

    const options = [];
    responseJSON?.results?.data?.map((resultItem) => {
      options.push({ value: resultItem.id, label: resultItem[optionLabel] });
    });

    setSelectedOptions(options);
  } catch (error) {
    console.error(`Error loading selected ${optionLabel}:`, error);
  }
};

export function formatArticleDate(date) {
  if (isToday(new Date(date))) {
    return `${differenceInHours(new Date(), new Date(date))} hours ago`;
  } else {
    return format(new Date(date), "MMMM d, yyyy' 'HH:mm:ss");
  }
}

export const currentUser = () => {
  const user = JSON.parse(localStorage.getItem("currentUser"));

  return user && user.name ? user : false;
};
