<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="primary" class="fixed-top">
            <b-container>
                <b-navbar-brand href="#">fmapts</b-navbar-brand>
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
                <b-collapse id="nav-collapse" is-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">

                        <b-nav-form>

                            <b-form-select
                                    v-show="regionOrArea==='ar'"
                                    v-model="area"
                                    :options="areaOptions"
                                    size="sm" class="mr-sm-2"
                                    @change="getProducts"

                            ></b-form-select>

                            <b-form-select
                                    v-show="regionOrArea==='rg'"
                                    v-model="region"
                                    :options="regionOptions"
                                    size="sm" class="mr-sm-2"
                                    @change="getProducts"
                            ></b-form-select>

                        </b-nav-form>

                    </b-navbar-nav>

                </b-collapse>


            </b-container>
        </b-navbar>

        <b-container class="mt-4">

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
                        <div class="address" v-for="(address,key) in product.addresses" :key="key">
                            <div class="address-line">
                                {{address.address_line}}
                            </div>
                            <div class="address-line2" v-show="address.address_line2.length > 0">
                                {{address.address_line2}}
                            </div>
                            <div class="address-line3" v-show="address.address_line3.length > 0">
                                {{address.address_line3}}
                            </div>
                            <div class="address-city-state-postcode d-flex flex-row flex-wrap">
                                <div class="address-city mr-1">{{address.city}}</div>
                                <div class="address-state mr-1">{{address.state}}</div>
                                <div class="address-postcode">{{address.postcode}}</div>
                            </div>
                            <div class="address-area d-flex flex-row flex-wrap">
                                <div class="mr-3">Area:</div>
                                <div v-for="(area,i) in address.area" :key="i" class="mr-1">
                                    {{area}}
                                </div>
                            </div>
                            <div class="address-region d-flex flex-row flex-wrap">
                                <div class="mr-3">Region</div>
                                <div v-for="(region,i) in address.region" :key="i" class="mr-1">
                                    {{region}}
                                </div>
                            </div>
                        </div>
                        {{product.productDescription}}
                    </b-card-text>
                </b-card>

            </div>

        </b-container>


    </div>

</template>

<script>
    export default {
        name: "MainComponent",

        data() {
            return {
                products: [],
                meta: [],
                filter: {
                    st: 'NSW',
                    cats: 'ACCOMM',
                    ar: null,
                    rg: null
                },
                region: null,
                area: null,
                areaOptions: [],
                regionOptions: [],
                regionOrArea: 'rg'
            }
        },

        mounted() {
            this.getProducts();
            this.getAreas();
            this.getRegions();
        },

        methods: {
            getProducts() {

                this.filter.rg = this.regionOrArea === 'rg' ? this.region : null;
                this.filter.ar = this.regionOrArea === 'ar' ? this.area : null;

                if (this.filter.rg === 'ar'){
                    this.area = null;
                    this.regionOrArea = 'ar';
                    return;
                }
                if (this.filter.ar === 'rg'){
                    this.region = null;
                    this.regionOrArea = 'rg';
                    return;
                }

                axios
                    .get(route('products', this.filter))
                    .then(response => this.displayProducts(response))
                    .catch(errors => this.handleErrors(errors));
            },
            handleErrors(errors) {
                console.log(errors);
            },
            displayProducts(response) {
                this.products = response.data.data;
                this.meta = response.data.meta
            },
            getAreas() {
                axios
                    .get(route('areas',{st:'NSW'}))
                    .then(response => this.setAreaOptions(response.data.data))
                    .catch(errors => this.handleErrors(errors));
            },
            setAreaOptions(areas){
                this.areaOptions = areas;
                this.areaOptions.unshift({value:null,text:'SELECT AREA'},{value:'rg',text:'SELECT REGION'});

            },
            getRegions() {
                axios
                    .get(route('regions',{st:'NSW'}))
                    .then(response => this.setRegionOptions(response.data.data))
                    .catch(errors => this.handleErrors(errors));
            },
            setRegionOptions(regions){
                this.regionOptions = regions;
                this.regionOptions.unshift({value:null,text:'SELECT REGION'},{value:'ar',text:'SELECT AREA'});
            }

        },


    }
</script>

<style lang="scss" scoped>

    .card.product {
        max-width: 30em;
    }

</style>