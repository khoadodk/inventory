<template> </template>

<script>
export default {
    created() {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        Toast.fire({
            icon: "success",
            title: "Sign Out Successfully!"
        });
        this.$router.push({ name: "/" });
    }
};
</script>
