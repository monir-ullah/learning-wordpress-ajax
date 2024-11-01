import { NavLink, Outlet } from "react-router-dom";
import "../CSS/style.css";
import { Button } from "@wordpress/components";

function App() {
  return (
    <div>
      <h1> Hello This is from React</h1>

      <nav id="nav-bar">
        <NavLink
          to=""
          className={({ isActive, isPending }) =>
            isPending ? "pending" : isActive ? "active" : ""
          }
        >
          About
        </NavLink>
        <NavLink
          to="/contact"
          className={({ isActive, isPending }) =>
            isPending ? "pending" : isActive ? "active" : ""
          }
        >
          Contact
        </NavLink>
        <NavLink
          to="/service"
          className={({ isActive, isPending }) =>
            isPending ? "pending" : isActive ? "active" : ""
          }
        >
          Service
        </NavLink>
      </nav>

      <div id="admin-panel-body">
        <Outlet />
      </div>
    </div>
  );
}

export default App;
