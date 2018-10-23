<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/merchant-account/account-type/form' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> Add</a>">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Invoice No</th>
              <th>Account No</th>
              <th>Payment Name</th>
              <th>Contact</th>
              <th>Email</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data, key in datatable['data']">
              <td>{{++key}}</td>
              <td>{{data.invoice_no}}</td>
              <td>{{data.account_no}}</td>
              <td>{{data.payment_lastname}} {{data.payment_firstname}}</td>
              <td>{{data.telephone}}</td>
              <td>{{data.email}}</td>
              <td>
                <button type="button" @click="OpenOrderProduct(data)" class="btn btn-primary"><i class="icon-eye"></i> View</button>
                <!--<button type="button" @click="initDelete(data.account_type_id)" class="btn btn-danger"><i class="icon-trash"></i> Delete</button>-->
              </td>
            </tr>
          </tbody>
        </table>
        <!--<ul class="pagination">
          <li class="page-item"><a class="page-link" href="#">Prev</a></li>
          <li class="page-item active">
            <a class="page-link" href="#">1</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">4</a></li>
          <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>-->
      </b-card>
    </div><!--/.row-->

    <!--modal Order Product List-->
    <order-product
        ref="childOrderProduct"
        @emitEvent="emitEvent()"
        v-on:event_child="eventChild"> 
    </order-product>
  </div>

</template>


<script>
  import OrderProduct from './_OrderProduct.vue'
	import {fetchList,deleteData} from '../../../../api/order/order'
  import Flash from '../../../../services/flash'
	import axios from 'axios'
	export default{
		data(){
			return{
        backUrl:'/order/order-list',
        datatable:[],
        flash: Flash.state
      }
    },
    components:{
      OrderProduct
    },
		created(){
      Flash.setLoading(true)
			this.getData();
		},
    methods:{
      eventChild: function() {
          this.$emit('event_child')
      },
      OpenOrderProduct(data){
          this.$refs.childOrderProduct.child_method(data)
      },
      getData() {
        fetchList().then(response => {
          this.datatable = response
        })
      },
      initDelete(id){
        var r = confirm("Are you sure to delete?");
        if (r == true) {
          deleteData(id).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
              this.getData();
            }else{
              Flash.setError(response.message)
              Flash.setLoading(true)
            }
          })
        }
      }
    }
	}
</script>