// API CP
$(document).ready(function () {
  const apiUrl = "https://geo.api.gouv.fr/communes?codePostal=";
  const format = "&format=json";

  let cp = $("#cp");
  let ville = $("#ville");
  let errorMessage = $("#error-message");

  $(cp).on("blur", function () {
    let code = $(this).val();
    let url = apiUrl + code + format;

    fetch(url, { method: "get" })
      .then((response) => response.json())
      .then((results) => {
        $(ville).find("option").remove();
        if (results.length) {
          $(errorMessage).text("").hide();
          $.each(results, function (key, value) {
            console.log(value.nom);
            $(ville).append(
              '<option value="' + value.nom + '">' + value.nom + "</option>"
            );
          });
        } else {
          if ($(cp).val()) {
            console.log("Ce code postal n'existe pas.");
            $(errorMessage)
              .text("Aucune commune ne correspond à ce code postal.")
              .show();
          } else {
            $(errorMessage).text("").hide();
          }
        }
      })
      .catch((err) => {
        console.log(err);
        $(ville).find("option").remove();
      });
  });

  

});

// notation par étoiles
window.onload = () => {
  const stars = document.querySelectorAll(".fa-star");

  const note = document.querySelector("#note");

  for (star of stars) {
    star.addEventListener("mouseover", function () {
      resetStars();
      this.style.color = "#bfa764";
      this.classList.add("fas");
      this.classList.remove("far");

      let previousStar = this.previousElementSibling;

      while (previousStar) {
        previousStar.style.color = "#bfa764";
        previousStar.classList.add("fas");
        previousStar.classList.remove("far");
        previousStar = previousStar.previousElementSibling;
      }
    });

    star.addEventListener("click", function () {
      note.value = this.dataset.value;
    });

    star.addEventListener("mouseout", function () {
      resetStars(note.value);
    });
  }

  function resetStars(note = 0) {
    for (star of stars) {
      if (star.dataset.value > note) {
        star.style.color = "#908da5;";
        star.classList.add("far");
        star.classList.remove("fas");
      } else {
        star.style.color = "#bfa764";
        star.classList.add("fas");
        star.classList.remove("far");
      }
    }
  }
};
// notation par étoiles

// preview image
const previewImage1 = (e) => {
  const preview = document.getElementById("preview1");
  preview.src = URL.createObjectURL(e.target.files[0]);
  preview.onload = () => URL.revokeObjectURL(preview.src);
};

$("#photo1").change(function () {
  $("#preview1").removeClass("d-none");
  $("#uploadPhoto1").addClass("d-none");
});

const previewImage2 = (e) => {
  const preview = document.getElementById("preview2");
  preview.src = URL.createObjectURL(e.target.files[0]);
  preview.onload = () => URL.revokeObjectURL(preview.src);
};

$("#photo2").change(function () {
  $("#preview2").removeClass("d-none");
  $("#uploadPhoto2").addClass("d-none");
});

const previewImage3 = (e) => {
  const preview = document.getElementById("preview3");
  preview.src = URL.createObjectURL(e.target.files[0]);
  preview.onload = () => URL.revokeObjectURL(preview.src);
};

$("#photo3").change(function () {
  $("#preview3").removeClass("d-none");
  $("#uploadPhoto3").addClass("d-none");
});

const previewImage4 = (e) => {
  const preview = document.getElementById("preview4");
  preview.src = URL.createObjectURL(e.target.files[0]);
  preview.onload = () => URL.revokeObjectURL(preview.src);
};

$("#photo4").change(function () {
  $("#preview4").removeClass("d-none");
  $("#uploadPhoto4").addClass("d-none");
});

const previewImage5 = (e) => {
  const preview = document.getElementById("preview5");
  preview.src = URL.createObjectURL(e.target.files[0]);
  preview.onload = () => URL.revokeObjectURL(preview.src);
};

$("#photo5").change(function () {
  $("#preview5").removeClass("d-none");
  $("#uploadPhoto5").addClass("d-none");
});
// preview image

// profil information

$("#lieu").change(function () {
  $("#newTown").toggleClass("d-none");
});

$(document).ready(function () {
  //tri Annonce index
  $("select#triAnnonce").change(function () {
    var tri = $(this).children("option:selected").val();
    console.log(tri);
    if (tri == "1") {
      $("#tri0").addClass("d-none");
      $("#tri1").removeClass("d-none");
      $("#tri2").addClass("d-none");
      $("#tri3").addClass("d-none");
    } else if (tri == "2") {
      $("#tri0").addClass("d-none");
      $("#tri1").addClass("d-none");
      $("#tri2").removeClass("d-none");
      $("#tri3").addClass("d-none");
    } else if (tri == "3") {
      $("#tri0").addClass("d-none");
      $("#tri1").addClass("d-none");
      $("#tri2").addClass("d-none");
      $("#tri3").removeClass("d-none");
    } else if (tri == "0") {
      $("#tri0").removeClass("d-none");
      $("#tri1").addClass("d-none");
      $("#tri2").addClass("d-none");
      $("#tri3").addClass("d-none");
    }
  });

  //tri tabs partie admin
  $("#gestionAnnonce").DataTable();
  $("#gestionCategorie").DataTable();
  $("#gestionMembre").DataTable();
  $("#gestionQr").DataTable();
  $("#gestionNote").DataTable();
});

// range price
$('input[type="range"]').val(0).change();
$(function () {
  var $document = $(document);
  var selector = "[data-rangeslider]";
  var $element = $(selector);
  var textContent = "textContent" in document ? "textContent" : "innerText";

  // Example functionality to demonstrate a value feedback
  function valueOutput(element) {
    var value = element.value;
    var output =
      element.parentNode.getElementsByTagName("output")[0] ||
      element.parentNode.parentNode.getElementsByTagName("output")[0];
    output[textContent] = value;
  }

  $document.on("input", 'input[type="range"], ' + selector, function (e) {
    valueOutput(e.target);
  });

  $element.rangeslider({
    polyfill: false,
    onInit: function () {
      valueOutput(this.$element[0]);
    },
    onSlide: function (position, value) {
      console.log("onSlide");
      console.log("position: " + position, "value: " + value);
    },
    onSlideEnd: function (position, value) {
      console.log("onSlideEnd");
      console.log("position: " + position, "value: " + value);
    },
  });
});
