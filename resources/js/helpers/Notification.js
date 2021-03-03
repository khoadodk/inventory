class Notification {
    success() {
        new Noty({
            type: "success",
            layout: "topRight",
            text: "Successsfully Done!",
            timeout: 1000
        }).show();
    }
    alert() {
        new Noty({
            type: "alert",
            layout: "topRight",
            text: "Are you sure!",
            timeout: 1000
        }).show();
    }
    error() {
        new Noty({
            type: "alert",
            layout: "topRight",
            text: "Something went wrong!",
            timeout: 1000
        }).show();
    }
    warning() {
        new Noty({
            type: "warning",
            layout: "topRight",
            text: "Oops, wrong! ",
            timeout: 1000
        }).show();
    }
    image_validation() {
        new Noty({
            type: "warning",
            layout: "topRight",
            text: "Image has to be less than 1 Mb",
            timeout: 1000
        }).show();
    }
    cart_success() {
        new Noty({
            type: "success",
            layou: "topRight",
            text: "Successfully Added!",
            timeout: 1000
        }).show();
    }
    cart_delete() {
        new Noty({
            type: "success",
            layou: "topRight",
            text: "Successfully Removed!",
            timeout: 1000
        }).show();
    }
}

export default Notification = new Notification();
