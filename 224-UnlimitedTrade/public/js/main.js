// navigation add hovered class to selected list item start
let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}
list.forEach((item) => item.addEventListener("mouseover", activeLink));
// navigation add hovered class to selected list item end

// toggle menu start
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};
// toggle menu end
