(function ($) {
  "use strict";
  $(document).ready(function () {
    $(".dl_add_licence").on("click", function (e) {
      $("#dl_licence_container").show();
      $("#dl_licence_view_container").hide();
    });
    $(".dl_view_licence").on("click", function (e) {
      $("#dl_licence_container").hide();
      $("#dl_licence_view_container").show();
    });

    // save licence type
    $("#digital_goods_dl_type").on("change", function (e) {
      e.preventDefault();
      console.log(e);
      console.log(dl_licence_obj.ajax_url);
      console.log(this);

      var dl_type = this.value;
      var pid = $(this).attr("data-pid");
      console.log(dl_type);
      console.log(pid);
      var data = {
        action: "save_dl_type_ajax",
        dl_type: dl_type,
        product_id: pid,
      };
      jQuery.post(dl_licence_obj.ajax_url, data, function (response) {
        localStorage.setItem("response2", JSON.stringify(response));
        window.location.href =
          window.location.pathname +
          window.location.search +
          window.location.hash;
      });
    });

    document.getElementById("dlresult").innerHTML = localStorage.getItem(
      "response"
    );
    document.getElementById("dlresult2").innerHTML = localStorage.getItem(
      "response2"
    );

    // save Licence data
    $("#dl_licence_add").on("click", function (e) {
      e.preventDefault();
      console.log(e);
      var type = $("#digital_goods_dl_type").find(":selected").text();
      var product_id = $(this).attr("data-id");
      var licence = $("#dl_licence_key").val() || "";
      var item = $("#dl_licence_item").val();
      var dl_login_id = $("#dl_login_id").val() || "";
      var dl_login_password = $("#dl_login_password").val() || "";
      var download_link = $("#dl_download_link").val() || "";

      if (type === "licence_key" && licence === "") {
        return alert("Add Licence key");
      } else if (type === "login_details" && dl_login_id === "") {
        return alert("Add login id");
      } else if (type === "download_link" && download_link === "") {
        return alert("Add download link");
      }
      console.log("product_id", product_id);
      console.log("licence", licence);
      console.log("item", item);

      var data = {
        action: "dl_licence_admin_ajax",
        type: type,
        product_id: product_id,
        licence: licence,
        item: item && item !== "" ? parseInt(item) : 1,
        login_id: dl_login_id,
        login_password: dl_login_password,
        download_link: download_link,
      };

      // var url = new URL(window.location.href);
      // url.searchParams.set('sp_poll_add', 'poll');
      // console.log(url);
      // console.log(url.href);
      console.log(data);
      console.log(dl_licence_obj.ajax_url);

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      jQuery.post(dl_licence_obj.ajax_url, data, function (response) {
        // $('#dl_licence_container').hide()
        // $('#dl_licence_view_container').show()
        // console.log('Voted Completed: ' + response);
        localStorage.setItem("response", JSON.stringify(response));
        // window.location.href =
        //   window.location.pathname +
        //   window.location.search +
        //   window.location.hash;
      });
    });

    // Table Execute
    var tableCont = document.querySelector("#tabla");

    function scrollHandle(e) {
      console.log(e);
      var scrollTop = e.target.scrollTop;
      e.target.querySelector("thead").style.transform =
        "translateY(" + scrollTop + "px)";
    }

    if (tableCont) {
      console.log("tttabls active");
      tableCont.addEventListener("scroll", scrollHandle);
    }
  });

  // csv validation
  $("#frmCSVImport").on("submit", function () {
    $("#response").attr("class", "");
    $("#response").html("");
    var fileType = ".csv";
    var regex = new RegExp("([a-zA-Z0-9s_\\.-:])+(" + fileType + ")$");
    if (!regex.test($("#file").val().toLowerCase())) {
      $("#response").addClass("error");
      $("#response").addClass("display-block");
      $("#response").html(
        "Invalid File. Upload : <b>" + fileType + "</b> Files."
      );
      return false;
    }
    return true;
  });
})(jQuery);
