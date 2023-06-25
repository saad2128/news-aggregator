import React, { useEffect, useState } from "react";
import { sendGetRequest, sendPostRequest } from "../ApiService";
import { currentUser } from "../Elements/Utils";
import { useForm } from "react-hook-form";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import PreferenceSection from "../Elements/PreferenceSection";

const Preferences = () => {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm();
  const [loading, setLoading] = useState(true);
  const [authors, setAuthors] = useState([]);
  const [sources, setSources] = useState([]);
  const [preference, setPreference] = useState({});
  const loggedUser = currentUser();

  useEffect(() => {
    setLoading(true);
    sendGetRequest(`preferences`, {
      Authorization: `Bearer ${loggedUser?.token}`,
    }).then(function (response) {
      if (response.status) {
        setAuthors(response.results.authors);
        setSources(response.results.sources);
        setPreference(JSON.parse(response.results.preference));
      }

      setLoading(false);
    });
  }, []);

  if (Array.isArray(preference?.authors) && preference?.authors?.length) {
    preference.authors = preference.authors.map((val) => Number(val));
  } else {
    preference.authors = [];
  }

  if (Array.isArray(preference?.sources) && preference?.sources?.length) {
    preference.sources = preference.sources.map((val) => Number(val));
  } else {
    preference.sources = [];
  }

  const onSubmit = (submittedData) => {
    const savePreference = sendPostRequest(
      "preferences",
      JSON.stringify(submittedData),
      {
        Authorization: `Bearer ${loggedUser?.token}`,
        "Content-Type": "application/json",
      }
    );

    toast.promise(savePreference, {
      pending: `Saving...`,
      success: `Preferences has been saved`,
      error: `Something went wrong, please try again`,
    });
  };

  return (
    <>
      <div className="mx-auto px-2 sm:px-6 lg:px-8">
        <div className="flex min-h-full flex-col justify-center py-6 sm:px-6 lg:px-8 w-full">
          <div className="mx-auto max-w-7xl px-6 lg:px-8">
            <div className="mx-auto max-w-2xl sm:text-center">
              <p className="mt-2 text-3xl font-bold tracking-tight  sm:text-4xl">
                Preferrences
              </p>
              <h2 className="text-base font-semibold leading-7 text-customBlue">
                Personalize your experience by choosing authors and sources you
                want to follow.
              </h2>
            </div>
          </div>

          <form
            className="space-y-8 divide-y divide-gray-200"
            method="post"
            onSubmit={handleSubmit(onSubmit)}
          >
            <div className="space-y-8 divide-y divide-gray-200">
              <div className="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div className="sm:col-span-3">
                  <PreferenceSection
                    title="Authors"
                    description="Select your preferred authors to customize your news feed."
                    options={authors}
                    loading={loading}
                    preference={preference}
                    register={register}
                    preferenceKey="authors"
                    keyProp="author_slug"
                    labelProp="author_name"
                  />
                </div>

                <div className="sm:col-span-3">
                  <PreferenceSection
                    title="Sources"
                    description="Select your preferred sources to customize your news feed."
                    options={sources}
                    loading={loading}
                    preference={preference}
                    register={register}
                    preferenceKey="sources"
                    keyProp="source_slug"
                    labelProp="source"
                  />
                </div>
              </div>
            </div>

            <div className="pt-5">
              <div className="flex justify-end">
                <button
                  type="submit"
                  className="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Save
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <ToastContainer />
    </>
  );
};

export default Preferences;
