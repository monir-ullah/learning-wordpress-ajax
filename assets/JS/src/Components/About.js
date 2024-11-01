import { useEffect, useState } from "react";

const About = () => {
  const [userInfo, setUserInfo] = useState({});

  const handleFormSubmit = (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);

    formData.append("action", "wp_admin_panel_form_data");
    formData.append("_ajax_nonce", js_variable._ajax_nonce);

    fetch(js_variable.ajax_url, {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        return response.json();
      })
      .then((response) => {
        console.log(response);
      });
  };

  useEffect(() => {
    fetch(
      js_variable.ajax_url +
        `?action=get_admin_panel_data&_ajax_nonce=${js_variable._ajax_nonce}`,
      {
        method: "GET",
      }
    )
      .then((response) => response.json())
      .then((response) => {
        setUserInfo(response.data);
        console.log(response.data);
      });
  }, []);

  return (
    <>
      <form onSubmit={handleFormSubmit}>
        <table className="form-table" role="presentation">
          <tbody>
            <tr>
              <th scope="row">
                <label htmlFor="user_name">Enter Your User Name</label>
              </th>
              <td>
                <input
                  name="user_name"
                  type="text"
                  id="user_name"
                  placeholder="Enter Your User Name"
                  className="regular-text code"
                  defaultValue={userInfo.user_name ? userInfo.user_name : ""}
                />
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label htmlFor="user_email">Enter Your Email</label>
              </th>
              <td>
                <input
                  name="user_email"
                  type="email"
                  id="user_email"
                  placeholder="Enter Your Email"
                  className="regular-text code"
                  defaultValue={userInfo.user_email ? userInfo.user_email : ""}
                />
              </td>
            </tr>

            <tr>
              <th scope="row">
                <label htmlFor="like_or_dislike">Do You Like It?</label>
              </th>
              <td>
                <label htmlFor="like_or_dislike">
                  <input
                    name="like_or_dislike"
                    type="checkbox"
                    id="like_or_dislike"
                    className="regular-text code"
                    defaultChecked={userInfo.like_or_dislike}
                  />
                  Check the box if You like it.
                </label>
              </td>
            </tr>
            <tr>
              <th scope="row"></th>
              <td>
                <p className="submit">
                  <button
                    type="submit"
                    name="submit"
                    className="button button-primary"
                  >
                    Save Changes
                  </button>
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </>
  );
};

export default About;
