<template>
    <div>
        <div class="row">
            <router-link to="/add-supplier" class="btn btn-primary"
                >Add A Supplier
            </router-link>
        </div>
        <br />
        <input
            type="text"
            v-model="searchTerm"
            class="form-control"
            style="width: 300px;"
            placeholder="Search Here"
        />

        <br />

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                    >
                        <h6 class="m-0 font-weight-bold text-primary">
                            Supplier List
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Photo</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Shop Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="supplier in filtersearch"
                                    :key="supplier.id"
                                >
                                    <td>{{ supplier.supplier_name }}</td>
                                    <td>
                                        <img
                                            :src="supplier.photo"
                                            id="em_photo"
                                        />
                                    </td>
                                    <th>{{ supplier.address }}</th>
                                    <td>{{ supplier.phone }}</td>
                                    <td>{{ supplier.shopname }}</td>
                                    <td>
                                        <router-link
                                            :to="{
                                                name: 'edit-supplier',
                                                params: { id: supplier.id }
                                            }"
                                            class="btn btn-sm btn-primary"
                                            >Edit</router-link
                                        >

                                        <a
                                            @click="deletesupplier(supplier.id)"
                                            class="btn btn-sm btn-danger"
                                            ><font color="#ffffff"
                                                >Delete</font
                                            ></a
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
        <!--Row-->
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
            suppliers: [],
            searchTerm: ""
        };
    },
    computed: {
        filtersearch() {
            return this.suppliers.filter(supplier => {
                return supplier.supplier_name.match(this.searchTerm);
            });
        }
    },

    methods: {
        allsupplier() {
            axios
                .get("/api/supplier/")
                .then(({ data }) => (this.suppliers = data))
                .catch();
        },
        deletesupplier(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    axios
                        .delete("/api/supplier/" + id)
                        .then(() => {
                            this.suppliers = this.suppliers.filter(supplier => {
                                return supplier.id != id;
                            });
                        })
                        .catch(err => {
                            console.log(err.response.data.message);
                            Toast.fire({
                                icon: "warning",
                                title: err.response.data.message
                            });
                        });
                    Swal.fire(
                        "Deleted!",
                        "Your file has been deleted.",
                        "success"
                    );
                }
            });
        }
    },
    created() {
        this.allsupplier();
    }
};
</script>

<style type="text/css">
#em_photo {
    height: 40px;
    width: 40px;
}
</style>
