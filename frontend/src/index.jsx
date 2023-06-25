import React from "react";
import ReactDOM from "react-dom/client";
import "./tailwindbase.scss";
import "react-loading-skeleton/dist/skeleton.css";
import App from "./core/App";

document.addEventListener("DOMContentLoaded", () => {
  ReactDOM.createRoot(document.getElementById("newsAggregatorRoot")).render(
    <App />
  );
});
