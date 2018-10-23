<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/bookings/form' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> New Booking</a>">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Account Master</th>
              <th>Booking Type</th>
              <th>Price</th>
              <th>Sector</th>
              <th>Booking Date</th>
              <th>Is Approved?</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data, key in datatable['data']">
              <td>{{++key}}</td>
              <td><a :href="'/#/merchant-account/account-master/'+data.account_master_id">{{data.merchant_name}} ({{data.merchant_id}})</a></td>
              <td>{{data.booking_type}}</td>
              <td>{{data.price}}</td>
              <td>{{data.origin}}-{{data.destination}}</td>
              <td>{{data.booking_date}}</td>
              <td>
                <span v-if="data.is_approved==1" class="badge badge-success">Approved</span>
                <span v-else class="badge badge-danger">Not Approved</span>
              </td>
              <td>
                <span v-if="data.status==1" class="badge badge-success">Processed</span>
                <span v-else-if="data.status==0" class="badge badge-warning">Pending</span>
                <span v-else class="badge badge-danger">Voided</span>
              </td>
              <td>
                <!--<button type="button" @click="viewEdit(data.booking_id)" class="btn btn-primary"><i class="icon-eye"></i> View</button>-->
                <b-form-fieldset>
                    <b-input-group>
                        <!-- Attach Left button -->
                        <b-input-group-button class="btn-xs" slot="left">
                            <b-dropdown text="Action" variant="primary">
                              <b-dropdown-item @click="viewEdit(data.booking_id)"><i class="icon-pencil"></i> View</b-dropdown-item>
                              <!--<b-dropdown-item><i class="icon-eye"></i>Void</b-dropdown-item>-->
                              <b-dropdown-item v-if="data.status!=2" @click="initAction(data.booking_id,1,data.account_id,data.product_id,data.quantity)"><i class="label label-success icon-check"></i> Approved</b-dropdown-item>
                              <b-dropdown-item v-if="data.status!=1" @click="initAction(data.booking_id,2,data.account_id,data.product_id,data.quantity)"><i class="icon-action-undo"></i> Void</b-dropdown-item>
                            </b-dropdown>
                        </b-input-group-button>
                    </b-input-group>
                </b-form-fieldset>
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
  </div>

</template>


<script>
	import {fetchList,deleteData,updateData} from '../../../../../api/booking/bookings'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
	export default{
		data(){
			return{
        backUrl:'/bookings',
        datatable:[],
        flash: Flash.state
      }
    },
		created(){
      Flash.setLoading(true)
			this.getData();
		},
    methods:{
      getData() {
        fetchList().then(response => {
          this.datatable = response
        })
      },
      viewEdit(id){
        this.$router.push(this.backUrl+'/'+id)
      },
      viewEdit(id){
        this.$router.push(this.backUrl+'/'+id)
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
      },
      initAction(booking_id,status,account_id,product_id,quantity){
        var result = confirm("Are you sure to convert?");
        if (result) {
          const data = {
                        status:status,
                        is_approved:status,
                        account_id:account_id,
                        product_id:product_id,
                        quantity:quantity
                       }
          updateData(data,booking_id).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
            }else{
              Flash.setError(response.message)
            }
          })
          this.getData()
        }else{
          this.getData()
        }
      }

    }
	}
</script>