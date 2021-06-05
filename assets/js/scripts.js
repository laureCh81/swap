// preview image 
const previewImage1 = e => {
    const preview = document.getElementById('preview1');
    preview.src = URL.createObjectURL(e.target.files[0]);
    preview.onload = () => URL.revokeObjectURL(preview.src);
 };

 $("#photo1").change(function () {
    $("#preview1").removeClass("d-none");
    $("#uploadPhoto1").addClass("d-none");
 });

 const previewImage2 = e => {
    const preview = document.getElementById('preview2');
    preview.src = URL.createObjectURL(e.target.files[0]);
    preview.onload = () => URL.revokeObjectURL(preview.src);
 };

 $("#photo2").change(function () {
    $("#preview2").removeClass("d-none");
    $("#uploadPhoto2").addClass("d-none");
 });

 const previewImage3 = e => {
    const preview = document.getElementById('preview3');
    preview.src = URL.createObjectURL(e.target.files[0]);
    preview.onload = () => URL.revokeObjectURL(preview.src);
 };

 $("#photo3").change(function () {
    $("#preview3").removeClass("d-none");
    $("#uploadPhoto3").addClass("d-none");
 });

 const previewImage4 = e => {
    const preview = document.getElementById('preview4');
    preview.src = URL.createObjectURL(e.target.files[0]);
    preview.onload = () => URL.revokeObjectURL(preview.src);
 };

 $("#photo4").change(function () {
    $("#preview4").removeClass("d-none");
    $("#uploadPhoto4").addClass("d-none");
 });

 const previewImage5 = e => {
    const preview = document.getElementById('preview5');
    preview.src = URL.createObjectURL(e.target.files[0]);
    preview.onload = () => URL.revokeObjectURL(preview.src);
 };

 $("#photo5").change(function () {
    $("#preview5").removeClass("d-none");
    $("#uploadPhoto5").addClass("d-none");
 });



