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
                                v-show="!showProduct && filterType==='ar'"
                                v-model="filter.ar"
                                :options="areaOptions"
                                size="sm" class="mr-sm-2"
                                @change="resetProducts"

                        ></b-form-select>

                        <b-form-select
                                v-show="!showProduct && filterType==='rg'"
                                v-model="filter.rg"
                                :options="regionOptions"
                                size="sm" class="mr-sm-2"
                                @change="resetProducts"
                        ></b-form-select>

                        <b-btn
                                v-show="showProduct"
                                variant="light"
                                type="button"
                                size="sm"
                                @click="hideProduct"
                        >BACK
                        </b-btn>

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
                        img-left
                        tag="article"
                        @click="loadProduct(product)"
                >
                    <b-card-text>
                        <div class="address" v-for="(address,key) in product.addresses" :key="key">
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
                    </b-card-text>
                </b-card>
                <infinite-loading :identifier="infiniteId" @infinite="loadProducts"></infinite-loading>

            </div>

            <div class="product" v-show="showProduct">
                <b-card
                        class="product"
                        :title="product.productName"
                        :img-src="product.multimedia[0].serverPath"
                        :img-alt="product.multimedia[0].altText"
                        img-left
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
                infiniteId: +new Date(),
                product: {
                    productName: null,
                    multimedia: [{serverPath: null, altText: null}],
                    addresses: [],
                    productDescription: null
                },
                showProduct: false,
                meta: [],
                filter: {
                    pge: 1,
                    st: 'NSW',
                    cats: 'ACCOMM',
                    ar: null,
                    rg: null
                },
                areaOptions: [],
                regionOptions: [],
                filterType: 'rg',
            }
        },

        mounted() {
            this.getAreas();
            this.getRegions();
        },

        methods: {
            loadProducts($state) {

                axios
                    .get(route('products', this.filter))
                    .then(response => this.displayProducts(response.data, $state))
                    .catch(errors => this.handleErrors(errors));

            },
            resetProducts() {

                // if the selection is one of the ones that switch the select, switch the visible select.
                if (this.filter.rg === 'ar') {
                    // switch to area selection
                    this.switchFilter('ar');

                } else if (this.filter.ar === 'rg') {
                    // switch to region selection
                    this.switchFilter('rg');

                }

                this.filter.pge = 1;
                this.products = [];
                this.infiniteId += 1;
            },
            switchFilter(filter) {
                this.filter.rg = null;
                this.filter.ar = null;
                this.filterType = filter;
            },
            handleErrors(errors) {
                console.log(errors);
            },
            displayProducts(data, $state) {
                if (data.data.length) {
                    this.filter.pge += 1;
                    this.products.push(...data.data);
                    $state.loaded();
                } else {
                    $state.complete();
                }
                this.meta = data.meta;

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
                if (item.productId !== this.product.productId) {
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
            hideProduct() {
                this.showProduct = false;
            }


        },


    }
</script>

<style lang="scss" scoped>

    .products .card.product {
        &:hover {
            box-shadow: 0 0 2px 2px #000;
            cursor: pointer;
        }

    }
    .product .card.product {
        img{
            max-width: 30em;
        }
    }
    @supports (grid-area: auto) {

        .product .card.product {

            display: grid;
            grid-template-columns: 1fr 2fr;
            grid-template-rows: 1fr;

            img{
                width:100%;
                max-width: none;
            }
        }
    }

</style>