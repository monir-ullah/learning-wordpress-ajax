import { createRoot } from "react-dom/client";
import App from "./Components/App";
import { createHashRouter, RouterProvider } from "react-router-dom";
import About from "./Components/About";
import Contact from "./Components/Contact";
import Service from "./Components/Service";

const container = document.getElementById("react-app");
const root = createRoot(container);

const router = createHashRouter([
  {
    path: "/",
    element: <App />,
    children: [
      {
        path: "",
        element: <About />,
      },
      {
        path: "/contact",
        element: <Contact />,
      },
      {
        path: "/service",
        element: <Service />,
      },
    ],
  },
]);

root.render(<RouterProvider router={router} />);
