(function ($) {
  "use strict";
  $(document).ready(function () {
    var quickAddExperience = document.getElementById("dl_rest_save");
    console.log(quickAddExperience);
    console.log(dl_rest_obj);

    if (quickAddExperience) {
      quickAddExperience.addEventListener("click", function (e) {
        console.log(`e`, e);
        var ourPostData = {
          title: document.getElementById("title").value,
          content: document.getElementById("content").value,
          subtitle: document.getElementById("company_name").value,
          timing_of_experience: document.getElementById("time_period").value,
          status: "publish",
        };

        console.log(ourPostData);
        var createPost = new XMLHttpRequest();
        createPost.open(
          "POST",
          dl_rest_obj.site_url + "/wp-json/wp/v2/post/1/meta"
        );
        createPost.setRequestHeader("X-WP-Nonce", dl_rest_obj.nonce);
        createPost.setRequestHeader(
          "Content-Type",
          "application/json;charset=UTF-8"
        );
        createPost.send(JSON.stringify(ourPostData));
        createPost.onreadystatechange = function () {
          if (createPost.readystate == 4) {
            if (createPost.status == 201) {
              document.querySelector('.data-api-post-1 [name="title"]').value =
                "";
              document.querySelector(
                '.data-api-post-1 [name="content"]'
              ).value = "";
              document.querySelector(
                '.data-api-post-1 [name="company_name"]'
              ).value = "";
              document.querySelector(
                '.data-api-post-1 [name="time_period"]'
              ).value = "";
            } else {
              alert("Error - try again");
            }
          }
        };
      });
    }
  });
})(jQuery);
