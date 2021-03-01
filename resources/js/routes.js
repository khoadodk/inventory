let login = require("./components/auth/login.vue").default;
let register = require("./components/auth/register.vue").default;
let forget = require("./components/auth/forget.vue").default;
let logout = require("./components/auth/logout.vue").default;
let home = require("./components/home.vue").default;

// Employee Components
let addemployee = require("./components/employee/create.vue").default;
let employee = require("./components/employee/index.vue").default;
let editemployee = require("./components/employee/edit.vue").default;
// Supplier Components
let addsupplier = require("./components/supplier/create.vue").default;
let supplier = require("./components/supplier/index.vue").default;
let editsupplier = require("./components/supplier/edit.vue").default;
// Category Components
let addcategory = require("./components/category/create.vue").default;
let category = require("./components/category/index.vue").default;
let editcategory = require("./components/category/edit.vue").default;
// Product Components
let addproduct = require("./components/product/create.vue").default;
let product = require("./components/product/index.vue").default;
let editproduct = require("./components/product/edit.vue").default;

export const routes = [
    { path: "/", component: login, name: "/" },
    { path: "/register", component: register, name: "register" },
    { path: "/forget", component: forget, name: "forget" },
    { path: "/logout", component: logout, name: "logout" },
    { path: "/home", component: home, name: "home" },
    // Employee Routes
    {
        path: "/add-employee",
        component: addemployee,
        name: "add-employee"
    },
    {
        path: "/employee",
        component: employee,
        name: "employee"
    },
    {
        path: "/edit-employee/:id",
        component: editemployee,
        name: "edit-employee"
    },
    // Supplier Routes
    {
        path: "/add-supplier",
        component: addsupplier,
        name: "add-supplier"
    },
    {
        path: "/supplier",
        component: supplier,
        name: "supplier"
    },
    {
        path: "/edit-supplier/:id",
        component: editsupplier,
        name: "edit-supplier"
    },
    // Category Routes
    {
        path: "/add-category",
        component: addcategory,
        name: "add-category"
    },
    {
        path: "/category",
        component: category,
        name: "category"
    },
    {
        path: "/edit-category/:id",
        component: editcategory,
        name: "edit-category"
    },
    // Product Routes
    {
        path: "/add-product",
        component: addproduct,
        name: "add-product"
    },
    {
        path: "/product",
        component: product,
        name: "product"
    },
    {
        path: "/edit-product/:id",
        component: editproduct,
        name: "edit-product"
    }
];
