<template>
    <div>
        <div class="row">
            <router-link to="/order/today" class="btn btn-primary"
                >Today Order
            </router-link>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">
                                            Search Order
                                        </h1>
                                    </div>

                                    <form
                                        class="user"
                                        @submit.prevent="searchDate"
                                    >
                                        <div class="form-group">
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <label
                                                        for="exampleFormControlTextarea1"
                                                        ><b
                                                            >Search By Date
                                                        </b></label
                                                    >
                                                    <input
                                                        type="date"
                                                        class="form-control"
                                                        id="exampleInputFirstName"
                                                        required=""
                                                        v-model="date"
                                                    />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-block"
                                            >
                                                Search
                                            </button>
                                        </div>
                                    </form>
                                    <hr />
                                    <div class="text-center"></div>
                                    <div class="text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <!-- Simple Tables -->
                        <div class="card">
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                            >
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Orders
                                </h6>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table align-items-center table-flush"
                                >
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Qty</th>
                                            <th>SubTotal</th>
                                            <th>Tax</th>
                                            <th>Total</th>
                                            <th>Pay</th>
                                            <th>Due</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="order in orders">
                                            <td>{{ order.name }}</td>
                                            <td>{{ order.qty }}</td>
                                            <td>{{ order.sub_total }}</td>
                                            <td>{{ order.tax }}</td>
                                            <td>${{ order.total }}</td>
                                            <td>${{ order.pay_amount }}</td>
                                            <td>${{ order.due }}</td>
                                            <td>
                                                <router-link
                                                    :to="{
                                                        name: 'order-detail',
                                                        params: {
                                                            id: order.id
                                                        }
                                                    }"
                                                    class="btn btn-sm btn-primary"
                                                    >View</router-link
                                                >
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    // created() {
    //     if (!User.loggedIn()) {
    //         this.$router.push({ name: "/" });
    //     }
    // },

    data() {
        return {
            date: "",
            orders: {}
        };
    },

    methods: {
        searchDate() {
            const data = { date: this.date };
            axios
                .post("/api/order/search", data)
                .then(({ data }) => (this.orders = data))
                .catch(error => (this.errors = error.response.data.errors));
        }
    }
};
</script>

<style type="text/css"></style>
