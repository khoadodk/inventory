<template>
    <div>
        <div class="container-fluid" id="container-wrapper">
            <div
                class="d-sm-flex align-items-center justify-content-between mb-4"
            >
                <h1 class="h3 mb-0 text-gray-800">Point of Sale</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="./">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        POS
                    </li>
                </ol>
            </div>

            <div class="row mb-3">
                <!-- Area Chart -->
                <div class="col-xl-5 col-lg-12 col-md-12">
                    <div class="card mb-4">
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                        >
                            <a class="btn btn-sm btn-info"
                                ><font color="#ffffff">Add Customer</font></a
                            >
                        </div>

                        <div class="table-responsive" style="font-size: 12px;">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="cart in carts" :key="cart.id">
                                        <td>{{ cart.prod_name }}</td>
                                        <td>
                                            <input
                                                type="text"
                                                readonly=""
                                                style="width: 15px;"
                                                :value="cart.prod_quantity"
                                            />
                                            <button
                                                @click.prevent="
                                                    increment(cart.id)
                                                "
                                                class="btn btn-sm btn-success"
                                            >
                                                +
                                            </button>
                                            <button
                                                @click.prevent="
                                                    decrement(cart.id)
                                                "
                                                class="btn btn-sm btn-danger"
                                                v-if="cart.prod_quantity >= 2"
                                            >
                                                -
                                            </button>
                                            <button
                                                class="btn btn-sm btn-danger"
                                                v-else=""
                                                disabled=""
                                            >
                                                -
                                            </button>
                                        </td>
                                        <td>${{ cart.prod_price }}</td>
                                        <td>${{ cart.sub_total }}</td>
                                        <td>
                                            <a
                                                @click="removeItem(cart.id)"
                                                class="btn btn-sm btn-primary"
                                                ><font color="#ffffff"
                                                    >X</font
                                                ></a
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <ul class="list-group">
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center"
                                >
                                    Total Quantity:
                                    <strong>{{ qty }} items</strong>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center"
                                >
                                    Sub Total:
                                    <strong>${{ subtotal }}</strong>
                                </li>

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center"
                                >
                                    Tax:
                                    <strong>{{ taxes.tax }}%</strong>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center"
                                >
                                    Total :
                                    <strong
                                        >${{
                                            (subtotal * taxes.tax) / 100 +
                                                subtotal
                                        }}
                                    </strong>
                                </li>
                            </ul>
                            <br />

                            <form @submit.prevent="posOrder">
                                <label>Customer Name</label>
                                <select
                                    class="form-control"
                                    v-model="customer_id"
                                >
                                    <option
                                        :value="customer.id"
                                        v-for="customer in customers"
                                        >{{ customer.name }}
                                    </option>
                                </select>

                                <label>Amount</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    required=""
                                    v-model="pay_amount"
                                />

                                <label>Due</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    required=""
                                    v-model="due"
                                />

                                <label>Paid By</label>
                                <select class="form-control" v-model="paidby">
                                    <option value="Cash">Cash</option>
                                    <option value="Check">Check</option>
                                    <option value="Card">Card</option>
                                </select>

                                <br />
                                <button type="submit" class="btn btn-success">
                                    Submit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Pie Chart -->

                <div class="col-xl-7 col-lg-12 col-md-12">
                    <div class="card mb-4">
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                        >
                            <h6 class="m-0 font-weight-bold text-primary">
                                Active Products
                            </h6>
                        </div>

                        <!--  Products By Category -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a
                                    class="nav-link active"
                                    id="home-tab"
                                    data-toggle="tab"
                                    href="#home"
                                    role="tab"
                                    aria-controls="home"
                                    aria-selected="true"
                                    >All Product
                                </a>
                            </li>

                            <li
                                class="nav-item"
                                v-for="category in categories"
                                :key="category.id"
                            >
                                <a
                                    class="nav-link"
                                    id="profile-tab"
                                    data-toggle="tab"
                                    href="#profile"
                                    role="tab"
                                    aria-controls="profile"
                                    aria-selected="false"
                                    @click="getProductsByCat(category.id)"
                                    >{{ category.category_name }}</a
                                >
                            </li>
                        </ul>

                        <!-- START TAB HOME -->
                        <div class="tab-content" id="myTabContent">
                            <div
                                class="tab-pane fade show active"
                                id="home"
                                role="tabpanel"
                                aria-labelledby="home-tab"
                            >
                                <div class="card-body">
                                    <input
                                        type="text"
                                        v-model="searchTerm"
                                        class="form-control"
                                        style="width: 560px;"
                                        placeholder="Search Product"
                                    />

                                    <div class="row">
                                        <div
                                            class="col-lg-3 col-md-3 col-sm-6 col-6"
                                            v-for="product in filtersearch"
                                            :key="product.id"
                                        >
                                            <button
                                                class="btn btn-sm"
                                                @click.prevent="
                                                    AddToCart(product.id)
                                                "
                                            >
                                                <div
                                                    class="card"
                                                    style="width: 8.5rem; margin-bottom: 5px;"
                                                >
                                                    <img
                                                        :src="
                                                            product.product_image
                                                        "
                                                        id="em_photo"
                                                        class="card-img-top"
                                                    />
                                                    <div class="card-body">
                                                        <h6 class="card-title">
                                                            {{
                                                                product.product_name
                                                            }}
                                                        </h6>
                                                        <span
                                                            class="badge badge-success"
                                                            v-if="
                                                                product.product_quantity >=
                                                                    1
                                                            "
                                                            >Available
                                                            {{
                                                                product.product_quantity
                                                            }}
                                                        </span>
                                                        <span
                                                            class="badge badge-danger"
                                                            v-else=" "
                                                            >Stock Out
                                                        </span>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  End TABS HOME -->

                            <div
                                class="tab-pane fade"
                                id="profile"
                                role="tabpanel"
                                aria-labelledby="profile-tab"
                            >
                                <input
                                    type="text"
                                    v-model="getsearchTerm"
                                    class="form-control"
                                    style="width: 560px;"
                                    placeholder="Search Product"
                                />

                                <div class="row">
                                    <div
                                        class="col-lg-3 col-md-3 col-sm-6 col-6"
                                        v-for="productsByCat in getfiltersearch"
                                        :key="productsByCat.id"
                                    >
                                        <button
                                            class="btn btn-sm"
                                            @click.prevent="
                                                AddToCart(productsByCat.id)
                                            "
                                        >
                                            <div
                                                class="card"
                                                style="width: 8.5rem; margin-bottom: 5px;"
                                            >
                                                <img
                                                    :src="
                                                        productsByCat.product_image
                                                    "
                                                    id="em_photo"
                                                    class="card-img-top"
                                                />
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        {{
                                                            productsByCat.product_name
                                                        }}
                                                    </h6>
                                                    <span
                                                        class="badge badge-success"
                                                        v-if="
                                                            productsByCat.product_quantity >=
                                                                1
                                                        "
                                                        >Available
                                                        {{
                                                            productsByCat.product_quantity
                                                        }}
                                                    </span>
                                                    <span
                                                        class="badge badge-danger"
                                                        v-else=" "
                                                        >Stock Out
                                                    </span>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Products By Category-->
                    </div>
                </div>
            </div>
        </div>
        <!--Row-->
    </div>
</template>

<script type="text/javascript">
export default {
    created() {
        if (!User.loggedIn()) {
            this.$router.push({ name: "/" });
        }
    },

    created() {
        this.allProduct();
        this.allCategory();
        this.allCustomer();
        this.getCartProduct();
        this.getTax();
        // Reload page after adding a add product to cart
        Reload.$on("AfterAdd", () => {
            this.getCartProduct();
        });
    },
    data() {
        return {
            customer_id: "",
            pay_amount: "",
            due: "",
            paidby: "",

            products: [],
            categories: "",
            productsByCat: [],
            searchTerm: "",
            getsearchTerm: "",
            customers: "",
            errors: "",
            carts: [],
            taxes: ""
        };
    },
    computed: {
        filtersearch() {
            return this.products.filter(product => {
                return product.product_name.match(this.searchTerm);
            });
        },
        getfiltersearch() {
            return this.productsByCat.filter(productsByCat => {
                return productsByCat.product_name.match(this.getsearchTerm);
            });
        },
        qty() {
            let sum = 0;
            for (let i = 0; i < this.carts.length; i++) {
                sum += parseFloat(this.carts[i].prod_quantity);
            }
            return sum;
        },
        subtotal() {
            let sum = 0;
            for (let i = 0; i < this.carts.length; i++) {
                sum +=
                    parseFloat(this.carts[i].prod_quantity) *
                    parseFloat(this.carts[i].prod_price);
            }
            return sum;
        }
    },

    methods: {
        // Cart Methods Here
        AddToCart(id) {
            axios
                .get("/api/addtocart/" + id)
                .then(() => {
                    Reload.$emit("AfterAdd");
                    Notification.cart_success();
                })
                .catch(err => console.log(err));
        },
        getCartProduct() {
            axios
                .get("/api/cart")
                .then(({ data }) => (this.carts = data))
                .catch(err => console.log(err));
        },
        removeItem(id) {
            axios
                .get("/api/cart/remove/" + id)
                .then(() => {
                    Reload.$emit("AfterAdd");
                    Notification.cart_delete();
                })
                .catch(err => console.log(err));
        },
        increment(id) {
            axios
                .get("/api/cart/increment/" + id)
                .then(() => {
                    Reload.$emit("AfterAdd");
                    Notification.success();
                })
                .catch(err => console.log(err));
        },
        decrement(id) {
            axios
                .get("/api/cart/decrement/" + id)
                .then(() => {
                    Reload.$emit("AfterAdd");
                    Notification.success();
                })
                .catch(err => console.log(err));
        },
        getTax() {
            axios
                .get("/api/tax")
                .then(({ data }) => (this.taxes = data))
                .catch(err => console.log(err));
        },
        posOrder() {
            let total = (this.subtotal * this.taxes.tax) / 100 + this.subtotal;
            var data = {
                qty: this.qty,
                subtotal: this.subtotal,
                customer_id: this.customer_id,
                paidby: this.paidby,
                pay_amount: this.pay_amount,
                due: this.due,
                tax: this.taxes.tax,
                total: total
            };

            axios
                .post("/api/posorder", data)
                .then(() => {
                    Notification.success();
                    this.$router.push({ name: "home" });
                })
                .catch(err => console.log(err));
        },

        // End Cart Methods
        allProduct() {
            axios
                .get("/api/product/")
                .then(({ data }) => (this.products = data))
                .catch(err => console.log(err));
        },
        allCategory() {
            axios
                .get("/api/category/")
                .then(({ data }) => (this.categories = data))
                .catch(err => console.log(err));
        },

        allCustomer() {
            axios
                .get("/api/customer/")
                .then(({ data }) => (this.customers = data))
                .catch(err => console.log(err));
        },

        getProductsByCat(id) {
            axios
                .get("/api/productsbycat/" + id)
                .then(({ data }) => (this.productsByCat = data))
                .catch(err => console.log(err));
        }
    }
};
</script>

<style type="text/css" scoped>
#em_photo {
    height: 100px;
    width: 134px;
}
</style>
