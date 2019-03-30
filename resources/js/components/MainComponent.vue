<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="primary" class="fixed-top">
            <b-container>
                <b-navbar-brand href="#">fmapts</b-navbar-brand>
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
                <b-collapse id="nav-collapse" is-nav>

                    <!-- Right aligned nav items -->
                    <b-navbar-nav class="ml-auto">

                        <b-form-select
                                v-show="!showProduct && regionOrArea==='ar'"
                                v-model="filter.ar"
                                :options="areaOptions"
                                size="sm" class="mr-sm-2"
                                @change="getProducts"

                        ></b-form-select>

                        <b-form-select
                                v-show="!showProduct && regionOrArea==='rg'"
                                v-model="filter.rg"
                                :options="regionOptions"
                                size="sm" class="mr-sm-2"
                                @change="getProducts"
                        ></b-form-select>

                        <b-btn
                                v-show="showProduct"
                                variant="light"
                                type="button"
                                @click="hideProduct"
                        >BACK</b-btn>

                    </b-navbar-nav>

                </b-collapse>


            </b-container>
        </b-navbar>

        <b-container class="mt-5 pt-2">

            <div class="products" v-show="!showProduct">

                <b-card
                        class="product"
                        v-for="product in products"
                        :key="product.productId"
                        :title="product.productName"
                        :img-src="product.productImage"
                        img-alt="Image"
                        img-top
                        tag="article"
                        @click="loadProduct(product)"
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

            <div class="product" v-show="showProduct">
                <b-card
                        class="product"
                        :title="product.productName"
                        :img-src="product.multimedia[0].serverPath"
                        :img-alt="product.multimedia[0].altText"
                        img-top
                        tag="article"
                >
                    <b-card-text>
                        <div class="address" v-for="(address,key) in product.addresses" :key="key"
                             v-show="address.attributeIdAddress==='PHYSICAL'">
                            <div class="address-line">
                                {{address.addressLine1}}
                            </div>
                            <div class="address-line2" v-show="address.addressLine2.length > 0">
                                {{address.addressLine2}}
                            </div>
                            <div class="address-city-state-postcode d-flex flex-row flex-wrap">
                                <div class="address-city mr-1">{{address.cityName}}</div>
                                <div class="address-state mr-1">{{address.stateName}}</div>
                                <div class="address-postcode">{{address.addressPostalCode}}</div>
                            </div>
                            <div class="address-area d-flex flex-row flex-wrap">
                                <div class="mr-3">Area:</div>
                                <div class="mr-1">
                                    {{address.areaName}}
                                </div>
                            </div>
                            <div class="address-region d-flex flex-row flex-wrap">
                                <div class="mr-3">Region</div>
                                <div v-for="(region,i) in address.productAddressDomesticRegionRelationship" :key="i"
                                     class="mr-1">
                                    {{region.domesticRegionName}}
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
                product: {
                    productName: null,
                    multimedia: [{serverPath: null, altText: null}],
                    addresses: [],
                    productDescription: null
                },
                showProduct: false,
                meta: [],
                filter: {
                    st: 'NSW',
                    cats: 'ACCOMM',
                    ar: null,
                    rg: null
                },
                areaOptions: [],
                regionOptions: [],
                regionOrArea: 'rg',
            }
        },

        mounted() {
            this.getProducts();
            this.getAreas();
            this.getRegions();
        },

        methods: {
            getProducts() {

                // if the selection is one of the ones that switch the select, switch the visible select.
                if (this.filter.rg === 'ar') {
                    // switch to area selection
                    this.switchFilter('ar');

                } else if (this.filter.ar === 'rg') {
                    // switch to region selection
                    this.switchFilter('rg');

                } else {

                    // an area or a region (or neither) has been selected, load the products.

                    axios
                        .get(route('products', this.filter))
                        .then(response => this.displayProducts(response))
                        .catch(errors => this.handleErrors(errors));
                }
            },
            switchFilter(filter) {
                this.filter.rg = null;
                this.filter.ar = null;
                this.regionOrArea = filter;
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
                    .get(route('areas', {st: 'NSW'}))
                    .then(response => this.setAreaOptions(response.data.data))
                    .catch(errors => this.handleErrors(errors));
            },
            setAreaOptions(areas) {
                this.areaOptions = areas;

                // prepend the unselected and the switch selection options
                this.areaOptions.unshift({value: null, text: 'SELECT AREA'}, {value: 'rg', text: 'SELECT REGION'});

            },
            getRegions() {
                axios
                    .get(route('regions', {st: 'NSW'}))
                    .then(response => this.setRegionOptions(response.data.data))
                    .catch(errors => this.handleErrors(errors));
            },
            setRegionOptions(regions) {
                this.regionOptions = regions;

                // prepend the unselected and the switch selection options
                this.regionOptions.unshift({value: null, text: 'SELECT REGION'}, {value: 'ar', text: 'SELECT AREA'});
            },
            loadProduct(item) {

                // only request it if we don't already have it
                if (item.productId !== this.product.productId){
                    axios
                        .get(route('product', {product: item.productId}))
                        .then(response => {
                            this.product = response.data.data;
                            this.showProduct = true;
                        })
                        .catch(errors => handleErrors(errors));
                } else {
                    this.showProduct = true;
                }

            },
            hideProduct(){
                this.showProduct = false;
            }


        },


    }
</script>

<style lang="scss" scoped>

    .card.product {
        max-width: 30em;
    }
    .products .card.product{
        &:hover{
            box-shadow: 0 0 2px 2px #000;
            cursor: pointer;
        }

    }

</style>