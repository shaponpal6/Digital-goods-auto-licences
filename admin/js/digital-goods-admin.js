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

      var dl_type = this.value;
      var pid = $(this).attr("data-pid");
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

    // save Licence data
    $("#dl_licence_add").on("click", function (e) {
      e.preventDefault();
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

      jQuery.post(dl_licence_obj.ajax_url, data, function (response) {
        // localStorage.setItem("response", JSON.stringify(response));
        window.location.href =
          window.location.pathname +
          window.location.search +
          window.location.hash;
      });
    });

    const csv2json = (str, delimiter = ",") => {
      const titles = str.slice(0, str.indexOf("\n")).split(delimiter);
      const rows = str.slice(str.indexOf("\n") + 1).split("\n");
      return rows.map((row) => {
        const values = row.split(delimiter);
        return titles.reduce(
          (object, curr, i) => (
            (object[curr.trim()] = values[i].trim()), object
          ),
          {}
        );
      });
    };

    // save Licence data
    $("#file").on("change", function (e) {
      e.preventDefault();
      const reader = new FileReader();
      reader.onload = handleFileLoad;
      reader.readAsText(e.target.files[0]);
    });

    function handleFileLoad(event) {
      const csv = event.target.result;
      var dl_type = $("#digital_goods_dl_type").find(":selected").text();
      var pid = $("#digital_goods_dl_type").attr("data-pid");
      let word_array = csv2json(csv);

      var data = {
        action: "imput_csv_ajax",
        dl_type: dl_type,
        product_id: pid,
        csv_data2: csv,
        csv_data: JSON.stringify(word_array),
      };
      jQuery.post(dl_licence_obj.ajax_url, data, function (response) {
        // localStorage.setItem("csv_data", data);
        window.location.href =
          window.location.pathname +
          window.location.search +
          window.location.hash;
      });
    }

    // Table Execute
    var tableCont = document.querySelector("#tabla");

    function scrollHandle(e) {
      var scrollTop = e.target.scrollTop;
      e.target.querySelector("thead").style.transform =
        "translateY(" + scrollTop + "px)";
    }

    if (tableCont) {
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
