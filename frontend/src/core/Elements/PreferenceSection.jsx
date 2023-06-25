import React from "react";
import Skeleton from "react-loading-skeleton";

const PreferenceSection = ({
  title,
  description,
  options,
  loading,
  preference,
  register,
  preferenceKey,
  keyProp,
  labelProp,
}) => {
  return (
    <div className="pt-8">
      <div>
        <h3 className="text-lg font-medium leading-6 text-gray-900">{title}</h3>
        <p className="mt-1 text-sm text-gray-500">{description}</p>
      </div>
      <div className="mt-6">
        <fieldset>
          {!loading && (
            <div className="text-base text-gray-500" aria-hidden="true">
              {`${options.length} ${title.toLowerCase()}`}
            </div>
          )}
          <div className="mt-4 space-x-4 flex flex-wrap">
            {loading && <Skeleton count={10} />}

            {options.map((option) => {
              return (
                <div
                  key={option[keyProp]}
                  className="relative flex items-start"
                >
                  <div className="flex h-5 items-center">
                    <input
                      id={`${preferenceKey}_check_${option.id}`}
                      type="checkbox"
                      className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                      value={option.id}
                      {...register(preferenceKey)}
                      defaultChecked={preference?.[preferenceKey]?.includes(
                        option.id
                      )}
                    />
                  </div>
                  <div className="ml-3 text-sm">
                    <label
                      htmlFor={`${preferenceKey}_check_${option.id}`}
                      className="font-medium text-gray-700"
                    >
                      {option[labelProp]}
                    </label>
                  </div>
                </div>
              );
            })}
          </div>
        </fieldset>
      </div>
    </div>
  );
};

export default PreferenceSection;
