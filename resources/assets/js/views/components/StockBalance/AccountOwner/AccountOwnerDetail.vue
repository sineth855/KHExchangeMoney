<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <div class="card">
        <div class="card-header">
            <div>
            <i class="fa fa-align-justify"></i> Account Owner Detail
            <span class="pull-right">
                <a @click="onBackList" href="javascript:void(0)" class="btn btn-default btn-sm"><i class="icon-reply"></i> Back List</a>
                <!--<button @click="viewAccount(0,1)" class="btn btn-primary btn-sm"><i class="icon-plus"></i> Add</button>-->
            </span>
            </div>
        </div>
        <account-detail-list 
          :account_owner_id="account_owner_id"
          :accountData="accountData" 
          v-on:event_child="eventChild">
        </account-detail-list>
      </div>
    </div><!--/.row-->
  </div>

</template>


<script>
	import {fetchList,fetchAccountDetail,deleteData} from '../../../../../api/stockBalance/accountOwner'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
  import AccountDetailList from './Component/AccountDetailList.vue'
  import ModalAccountForm from './Component/ModalAccountForm.vue'

	export default{
    props:['id'],
		data(){
			return{
        backUrl:'/stocks/account-owner',
        datatable:[],
        flash: Flash.state,
        account_owner_id:this.id,
        accountData:[],
        tabIndex:0
      }
    },
		created(){
      Flash.setLoading(true)
			this.getData();
		},
    components:{
      AccountDetailList,
      ModalAccountForm
    },
    methods:{
      onBackList(){
        this.$router.push(this.backUrl)
      },
      eventChild: function(accou) {
        this.getData()
      },
      eventParent: function() {
          this.$emit('event_parent', 1)
      },
      getData() {
        this.tabIndex = 0
        Flash.setLoading(true)
        fetchAccountDetail(this.account_owner_id).then(response => {
          this.accountData = response['data']
          Flash.setLoading(false)
        })
      },
      viewAccount(id){
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
      }
    }
	}
</script>