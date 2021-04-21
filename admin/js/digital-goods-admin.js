(function ($) {
  "use strict";
  $(document).ready(function () {
    $(".dl_add_licence").on("click", function (e) {
      $("#dl_licence_add").html("Add New");
      $("#dl_licence_add").attr("data-row-id", "");
      $("#dl_licence_add").attr("data-type", "add");
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

    // add/update Licence data
    $("#dl_licence_add").on("click", function (e) {
      e.preventDefault();
      var type = $("#digital_goods_dl_type").find(":selected").text();
      var product_id = $(this).attr("data-id");
      var row_id = $(this).attr("data-row-id");
      var updateType = $(this).attr("data-type");
      var licence = $("#dl_licence_key").val() || "";
      var item = $("#dl_licence_item").val() || 1;
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
        row_id: row_id,
        update_type: updateType,
        licence: licence,
        item: item && item !== "" ? parseInt(item) : 1,
        login_id: dl_login_id,
        login_password: dl_login_password,
        download_link: download_link,
      };
      // console.log("data :>> ", data);

      jQuery.post(dl_licence_obj.ajax_url, data, function (response) {
        // localStorage.setItem("response", JSON.stringify(response));
        window.location.href =
          window.location.pathname +
          window.location.search +
          window.location.hash;
      });
    });

    // delete data
    $(".dlRowDelete").on("click", function (event) {
      event.preventDefault();
      event.stopPropagation();
      event.stopImmediatePropagation();
      const row_id = event.target.dataset.id;
      var data = {
        action: "dl_licence_delete_ajax",
        row_ids: row_id,
        action_type: "delete",
      };
      // console.log("data :>> ", data);

      jQuery.post(dl_licence_obj.ajax_url, data, function (response) {
        // localStorage.setItem("response", JSON.stringify(response));
        window.location.href =
          window.location.pathname +
          window.location.search +
          window.location.hash;
      });
    });

    // delete all data
    // $("#dlCheckAll4Delete").on("click", function (event) {
    //   // If the checkbox is checked, display the output text
    //   console.log("$(this) :>> ", $(this));
    //   var isCheck = document.querySelector("#dlCheckAll4Delete").checked;
    //   let checkboxes = document.querySelectorAll(".dlMultiDelete");
    //   checkboxes.forEach(function (ele) {
    //     console.log("ele :>> ", ele);
    //     ele.checked = !!isCheck;
    //   });
    // });

    // Check all data
    var checkbox = document.getElementById("dlCheckAll4Delete");
    checkbox.addEventListener("click", function (event) {
      let checkboxes = document.querySelectorAll(".dlMultiDelete");
      checkboxes.forEach(function (ele) {
        ele.checked = !!checkbox.checked;
      });
    });

    $("#dl_delete_all").on("click", function (event) {
      console.log("event :>> ", event);
      event.preventDefault();
      event.stopPropagation();
      event.stopImmediatePropagation();

      var checkedVals = $(".dlMultiDelete:checkbox:checked")
        .map(function (ele) {
          console.log("ele :>> ", ele);
          console.log("this :>> ", this);
          return $(this).attr("data-id");
        })
        .get();
      console.log("checkedVals :>> ", checkedVals);
      if (checkedVals.length < 1) {
        $("#dlLog").html("No items Selected to delete.");
        return;
      }
      const row_ids = checkedVals.join(",");
      console.log(row_ids);

      var data = {
        action: "dl_licence_delete_ajax",
        row_ids: row_ids,
        action_type: "deleteAll",
      };
      // console.log("data :>> ", data);

      jQuery.post(dl_licence_obj.ajax_url, data, function (response) {
        // localStorage.setItem("response", JSON.stringify(response));
        $("#dlLog").html("Successfully Deleted all items");
        console.log("response :>> ", response);

        const rows = document.querySelectorAll(".dlMultiDelete");
        if (rows) {
          rows.forEach(function (ele) {
            // console.log("ele :>> ", ele);
            // console.log("ele :>> ", ele.dataset.id);
            let rowId = ele.dataset.id || 0;
            if (checkedVals.indexOf(rowId) !== -1) {
              console.log("ele.11111 :>> ", ele.offsetParent);
              console.log("ele.22222 :>> ", ele.parentNode);
              console.log("ele.3333 :>> ", ele.parentNode.parentElement);
              ele.parentNode.parentElement.remove();
            }
          });
        }

        // window.location.href =
        //   window.location.pathname +
        //   window.location.search +
        //   window.location.hash;
      });
    });

    // set Update data
    $(".dlRowEdit").on("click", function (event) {
      event.preventDefault();
      event.stopPropagation();
      event.stopImmediatePropagation();
      const id = event.target.dataset.id;
      const tr = event.target.offsetParent.parentNode;
      const getVal = (key) =>
        tr.querySelector(key) && tr.querySelector(key).innerText;

      const licence = getVal(".dlRowLicence") || "";
      const total = getVal(".dlRowTotal") || "";
      const downloadLink = getVal(".dlRowDownloadLink") || "";
      const loginId = getVal(".dlRowLoginId") || "";
      const loginPassword = getVal(".dlRowLoginPassword") || "";
      // const licence2 = tr.find('[class*="dlRowLicence"]').first().text();
      $("#dl_licence_key").val(licence);
      $("#dl_licence_item").val(total);
      $("#dl_download_link").val(downloadLink);
      $("#dl_login_id").val(loginId);
      $("#dl_login_password").val(loginPassword);

      $("#dl_licence_add").html("Update");
      $("#dl_licence_add").attr("data-row-id", id);
      $("#dl_licence_add").attr("data-type", "update");

      $("#dl_licence_container").show();
    });

    const csv2json = (str, delimiter = ",") => {
      const titles = str.slice(0, str.indexOf("\n")).split(delimiter);
      const rows = str.slice(str.indexOf("\n") + 1).split("\n");
      return rows.map((row) => {
        const values = row.split(delimiter);
        return titles.reduce(
          (object, curr, i) => (
            (object[curr.trim()] = values[i] && values[i].trim()), object
          ),
          {}
        );
      });
    };

    // save Licence data
    $("#dlCSVFile").on("change", function (e) {
      e.preventDefault();
      var fileType = ".csv";
      var regex = new RegExp("([a-zA-Z0-9s_\\.-:])+(" + fileType + ")$");
      if (!regex.test($("#dlCSVFile").val().toLowerCase())) {
        $("#dlLog").addClass("error");
        $("#dlLog").addClass("display-block");
        $("#dlLog").html(
          "Invalid File. Upload : <b>" + fileType + "</b> Files."
        );
      } else {
        $("#dlLog").html("Loading & Validating.....");
        const reader = new FileReader();
        reader.onload = handleFileLoad;
        reader.readAsText(e.target.files[0]);
      }
    });

    function handleFileLoad(event) {
      const csv = event.target.result;
      var dl_type = $("#digital_goods_dl_type").find(":selected").text();
      var pid = $("#digital_goods_dl_type").attr("data-pid");
      let data = csv2json(csv);
      $("#dlLog").addClass("error");
      $("#dlLog").addClass("display-block");
      // console.log("word :>> ", data);
      if (data.length < 1) {
        $("#dlLog").html("No Data Found");
        return;
      }
      $("#dlLog").html("Validating with Licence Type : " + dl_type + ".....");
      if (dl_type === "licence_key") {
        if (data[0]["product_key"] && data[0]["limit"]) {
          $("#dlLog").html("Please Wait. Inserting data....");
        } else {
          $("#dlLog").html(
            "Validation Fail. Make sure You have <b>product_key</b> and <b>limit</b> fields in CSV file."
          );
          return;
        }
      } else if (dl_type === "login_details") {
        if (data[0]["login_id"] && data[0]["login_password"]) {
          $("#dlLog").html("Please Wait. Inserting data....");
        } else {
          $("#dlLog").html(
            "Validation Fail. Make sure You have <b>login_id</b> and <b>login_password</b> fields in CSV file."
          );
          return;
        }
      } else if (dl_type === "download_link") {
        if (data[0]["download_link"] && data[0]["serial_key"]) {
          $("#dlLog").html("Please Wait. Inserting data....");
        } else {
          $("#dlLog").html(
            "Validation Fail. Make sure You have <b>download_link</b> and <b>serial_key</b> fields in CSV file."
          );
          return;
        }
      }
      var action = {
        action: "imput_csv_ajax",
        dl_type: dl_type,
        product_id: pid,
        csv_data: JSON.stringify(data),
      };
      jQuery.post(dl_licence_obj.ajax_url, action, function (response) {
        // localStorage.setItem("csv_data", data);
        // console.log("response :>> ", response);
        var result = parseInt(response) || 0;
        // console.log("response :>> ", result);
        if (result > 0) {
          $("#dlLog").html("Successfully inserted data.");
          window.location.href =
            window.location.pathname +
            window.location.search +
            window.location.hash;
        } else {
          $("#dlLog").html(
            "No Data inserted. Make sure you have used valid <b>csv</b> file. <br/> Tips: Remove addiation space at the end of <b>csv</b> file."
          );
        }
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
        "Invalid File. Upload : <br>" + fileType + "</br> Files."
      );
      return false;
    }
    return true;
  });
})(jQuery);
