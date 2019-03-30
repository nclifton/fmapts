<template>
    <b-container fluid>

        <b-navbar toggleable="lg" type="dark" variant="primary">

            <b-navbar-brand href="#">fmapts</b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>


        </b-navbar>

        <div class="products">
            <b-card
                    class="product"
                    v-for="product in products"
                    :key="product.productId"
                    :title="product.productName"
                    :img-src="product.productImage"
                    img-alt="Image"
                    img-top
                    tag="article"
            >
                <b-card-text>
                    {{product.productDescription}}
                </b-card-text>
            </b-card>

        </div>

    </b-container>

</template>

<script>
    export default {
        name: "MainComponent",

        data() {
            return {
                products: [],
                filter: {
                    state: 'NSW'
                }
            }
        },

        mounted (){
            this.getProducts();
        },

        methods: {
            getProducts(){

                axios
                    .get(route('products',this.filter))
                    .then(response => this.displayProducts(response))
                    .catch(errors => this.handleErrors(errors));

            },
            handleErrors(errors){
                console.log(errors);
            },
            displayProducts(response){
                this.products = response.data.data;
            }
        }

    }
</script>

<style lang="scss" scoped>

    .card.product{
        max-width:30em;
    }

</style>