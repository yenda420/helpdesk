let navbar = document.querySelector('.header .navbar');
let accountBox = document.querySelector('.header .account-box');

document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active');
   accountBox.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () => {
   accountBox.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () => {
   navbar.classList.remove('active');
   accountBox.classList.remove('active');
}

function confirmDeletingUser() {
   return confirm('Are you sure you want to delete this user?');
}

function confirmDeletingAdmin() {
   return confirm('Are you sure you want to delete this admin user?');
}

function confirmDeletingRequest() {
   return confirm('Are you sure you want to delete this request?');
}

function confirmDeletingTicket() {
   return confirm('Are you sure you want to delete this ticket?');
}

function confirmDeletingTicketType() {
   return confirm('Are you sure you want to delete this ticket type?');
}

function confirmDeletingDepartment() {
   return confirm('Are you sure you want to delete this department?');
}

function confirmChangingTicketType() {
   return confirm('Are you sure you want to change this ticket type?');
}

function confirmChangingDepartments() {
   return confirm('Are you sure you want to change the departments?');
}