import React, { useEffect } from "react";
import Header from "./Elements/Header";
import AppRoutes from "./AppRoutes";
import { BrowserRouter } from "react-router-dom";

const App = () => {
  useEffect(() => {}, []);

  return (
    <BrowserRouter>
      <Header />
      <div className="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <AppRoutes />
      </div>
    </BrowserRouter>
  );
};

export default App;
