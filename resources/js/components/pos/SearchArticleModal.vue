<template>
  <div class="modal fade" id="searchArticalModal" tabindex="-1" aria-labelledby="searchArticalModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="searchArticalModalLabel">Search Article</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="search_by_brand">Brand</label>
              <select id="search_by_brand" class="form-control">
                <option value="">Select brand</option>
                <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                  {{ brand.name }}
                </option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label for="search_by_product_type">Product Type</label>
              <select id="search_by_product_type" class="form-control">
                <option value="">Select Product Type</option>
                <option v-for="productType in productTypes" :key="productType.id" :value="productType.id">
                  {{ productType.name }}
                </option>
              </select>
            </div>
          </div>

          <table class="table table-striped table-bordered w-100" id="search-article-modal">
            <thead>
              <tr>
                <th>#</th>
                <th>Article Code</th>
                <th>Manufacture Code</th>
                <th>Department</th>
                <th>Brand</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="sizeSelectionModal" tabindex="-1" aria-labelledby="sizeSelectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sizeSelectionModalLabel">Select Size</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div v-if="selectedProduct && selectedProduct.sizes">
            <div class="row">
              <div v-for="size in selectedProduct.sizes" :key="size.id" class="col-md-2 text-center">
                <div class="size-box mb-3" :class="{ selected: size.id === selectedSize }" @click="selectedSize = size.id">{{
                  size.size_detail.size }}</div>
              </div>
            </div>
          </div>
          <button class="btn btn-primary" @click="confirmSizeSelection" :disabled="!selectedSize">Confirm
            Selection</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="colorSelectionModal" tabindex="-1" aria-labelledby="colorSelectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="colorSelectionModalLabel">Select Color</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div v-if="selectedProduct && selectedProduct.colors">
            <div class="row">
              <div v-for="color in selectedProduct.colors" :key="color.id" class="col-md-3 text-center">
                <div class="color-box d-block m-auto" :class="{ selected: color.id === selectedColor }"
                  @click="selectedColor = color.id"
                  v-bind:style="{ backgroundColor: color.color_detail.ui_color_code }"></div>
                <label class="form-check-label" :for="color">{{ color.color_detail.name }}</label>
              </div>
            </div>
          </div>
          <button class="btn btn-primary mt-3" @click="confirmColorSelection" :disabled="!selectedColor">Confirm
            Selection</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="stocksDetailModal" tabindex="-1" aria-labelledby="stocksDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="stocksDetailModalLabel">Stocks Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div v-if="stocksDetail && stocksDetail.product">
            <div class="mb-3" v-for="(branch, index) in stocksDetail.branches" :key="index">
              <div class="d-flex justify-content-between">
                <h6><strong>{{ branch.name }}</strong></h6>
              </div>
              <!-- {{ stocksDetail.product.colors }} -->
              <div class="table-responsive">
                <table class="table mb-0 table-bordered">
                  <tbody>
                    <tr>
                      <th scope="row" class="p-1">Size</th>
                      <th class="p-1" v-for="(size, index) in stocksDetail.product.sizes" :key="index">{{ size.size_detail.size }}</th>
                    </tr>

                    <tr v-for="(color, index) in stocksDetail.product.colors" :key="index">
                      <th class="d-flex p-1">
                        <div class="me-1 d-color-code" :style="{ background: color.color_detail.ui_color_code }"></div>
                        <h6 class="m-0">{{ color.color_detail.name }} ({{ color.color_detail.code }})</h6>
                      </th>
                      <td class="p-1" v-for="(size, index) in stocksDetail.product.sizes" :key="index">
                        <div v-if="branch.id===1">
                          <strong>{{ stocksDetail.product.quantities.find(item => item.product_size_id === size.id && item.product_color_id === color.id)?.quantity || 0 }}</strong> /
                          {{ stocksDetail.product.quantities.find(item => item.product_size_id === size.id && item.product_color_id === color.id)?.total_quantity || 0 }}
                        </div>
                        <div v-else>
                          <strong>{{ branch.inventory.find(item => item.product_size_id === size.id && item.product_color_id === color.id)?.quantity || 0 }}</strong> /
                          {{ branch.inventory.find(item => item.product_size_id === size.id && item.product_color_id === color.id)?.total_quantity || 0 }}
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>



          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { EventBus } from '@/eventBus';

export default {
  props: {
    brands: {
      type: Array,
      required: true
    },
    productTypes: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      table: null,
      selectedProduct: null,
      selectedSize: null,
      selectedColor: null,
      stocksDetail: null
    };
  },
  methods: {
    async confirmColorSelection() {
      $('#colorSelectionModal').modal('hide');

      const response = await axios.post('/api/quick-validate-item', {
        id: this.selectedProduct.id,
        sizeId: this.selectedSize,
        colorId: this.selectedColor,
        userId: window.config.userId
      });

      if (response.data.success) {
        const article = response.data.product;

        if (article.available_quantity === 0) {
          Swal.fire({
            title: 'Warning!',
            text: 'Product is out of stock. Do you still want to add this product?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Add Product',
            cancelButtonText: 'No, Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              EventBus.quickAddArticle = {
                article: article,
                timestamp: Date.now(),
              };
            }
          });
        } else {
          EventBus.quickAddArticle = {
            article: article,
            timestamp: Date.now(),
          };
        }
      } else {
        Swal.fire({
          title: 'Error!',
          text: response.data.message,
          icon: 'error',
          confirmButtonText: 'Okay'
        });
      }
    },
    confirmSizeSelection() {
      $('#sizeSelectionModal').modal('hide');
      $('#colorSelectionModal').modal('show');
    },
    initializeDataTable() {
      $('#search_by_product_type').chosen({
      width: "100%"
    });

      const vm = this;

      this.table = $('#search-article-modal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '/get-products',
          data: (d) => {
            d.brand_id = $('#search_by_brand').val();
            d.product_type_id = $('#search_by_product_type').val();
            d.page = Math.floor(d.start / d.length) + 1;
          },
        },
        columns: [
          {
            title: '#',
            data: 'id',
          },
          {
            title: 'Article Code',
            data: 'article_code',
          },
          {
            title: 'Manufacture Code',
            data: 'manufacture_code',
          },
          {
            title: 'Department',
            data: 'department.name',
          },
          {
            title: 'Brand',
            data: 'brand.name',
          },
          {
            title: 'Action',
            data: null,
            render: function (data, type, row) {
              let tempArticle = {
                id: row.id,
                colors: row.colors,
                sizes: row.sizes,
              };

              return `
                <button class="btn btn-primary btn-sm py-0 px-1 pick-product-for-sale" data-product='${JSON.stringify(tempArticle)}'>
                  <i class="fas fa-hand-holding-usd"></i>
                </button>
                <button class="btn btn-primary btn-sm py-0 px-1 view-stock" data-product-id="${row.id}">
                  <i class="fas fa-eye"></i>
                </button>
              `;
            },
          },
        ],
        order: [[0, 'desc']],
        drawCallback: function () {
          $('#search-article-modal th, #search-article-modal td').addClass('p-1');

          $('.pick-product-for-sale').on('click', (event) => {
            const product = JSON.parse($(event.currentTarget).attr('data-product'));
            vm.pickProduct(product);
          });

          $('.view-stock').on('click', (event) => {
            const productId = $(event.currentTarget).attr('data-product-id');
            vm.viewStocks(productId);
          });
        },
      });
    },
    pickProduct(product) {
      this.selectedSize = null;
      this.selectedColor = null;
      this.selectedProduct = product;
      $('#searchArticalModal').modal('hide');
      $('#sizeSelectionModal').modal('show');
    },
    async viewStocks(productId) {
      const stocks = await axios.get(`/api/product-stocks/${productId}`);
      this.stocksDetail = {
        branches: stocks.data.branches,
        product: stocks.data.product
      }

      $('#stocksDetailModal').modal('show');
    },
    reloadDataTable() {
      if (this.table) {
        this.table.ajax.reload();
      }
    },
  },
  mounted() {
    $('#search_by_brand').chosen({
      width: "100%"
    });

    $('#search_by_product_type').chosen({
      width: "100%"
    });

    $('#search_by_brand').on('change', () => {
      this.reloadDataTable();
    });

    $('#search_by_product_type').on('change', () => {
      this.reloadDataTable();
    });

    this.initializeDataTable();
  }
};
</script>