let toast = document.querySelector('#MessageFlash');
toast.style.opacity = 'block';
setTimeout(function() {
    toast.style.display = 'none';
}, 3000);

// Thêm
var modal = document.getElementById("myModal");
var btn = document.getElementById("openModalBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}


// Sửa
const modalEdit = document.getElementById("myModalEdit");
const btnEdit = document.getElementById("openModalBtnEdit");
const spanEdit = document.getElementsByClassName("closeEdit")[0]; // Sửa chỉ số index thành 0

// Mở modal khi nút được click
btnEdit.onclick = function(event) {
  modalEdit.style.display = "block";
  event.stopPropagation(); // Ngăn chặn lan truyền sự kiện
}

// Đóng modal khi nút đóng được click
spanEdit.onclick = function(event) {
  modalEdit.style.display = "none";
  event.stopPropagation(); // Ngăn chặn lan truyền sự kiện
}

// window.onclick = function(event) {
//   if (event.target == modal) {
//     modal.style.display = "none";
//   }
// }
