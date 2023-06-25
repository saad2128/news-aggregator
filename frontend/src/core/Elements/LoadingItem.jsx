import React from "react";
import Skeleton from "react-loading-skeleton";

const LoadingItem = () => {
  return (
    <div
      key="loading-item"
      className="flex flex-col overflow-hidden rounded-lg shadow-lg"
    >
      <div className="flex-shrink-0">
        <Skeleton height={190} />
      </div>
      <div className="flex flex-1 flex-col justify-between bg-white p-6">
        <div className="flex-1">
          <Skeleton width={100} />
          <Skeleton count={2} height={20} />
        </div>

        <Skeleton count={4} height={10} inline={true} />

        <div className="mt-6">
          <Skeleton count={2} width={200} />
        </div>
      </div>
    </div>
  );
};

export default LoadingItem;
