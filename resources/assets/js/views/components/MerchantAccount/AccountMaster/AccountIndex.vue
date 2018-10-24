<template>
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <!--<b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>-->

      <div v-if="showAccountList" class="card">
        <div class="card-header">
            <div>
            <i class="fa fa-align-justify"></i> Account
            <span class="pull-right">
                <a @click="onBackList" href="javascript:void(0)" class="btn btn-default btn-sm"><i class="icon-reply"></i> Back List</a>
                <!--<button @click="viewAccount(0,1)" class="btn btn-primary btn-sm"><i class="icon-plus"></i> Add</button>-->
            </span>
            </div>
        </div>
        <account-detail-list 
          :account_id="account_id"
          :accountData="accountData"
          :accountCurrencyData="accountCurrencyData"
          :showAccountList="showAccountList"
          :account_master_id="account_master_id"
          v-on:event_child="eventChild">
        </account-detail-list>
      </div>
      <!--modal account-->
      <modal-account-form
        ref="child"
        :options="{ responsive: true, maintainAspectRatio: false }"
        :account_no="account_no"
        :flag_account="flag_account"
        :account_master_id="account_master_id"
        :formData="formData"
        v-on:event_account="eventAccount">
      </modal-account-form>

      <!--<b-card header="<i class='fa fa-align-justify'></i> List Data <span class='pull-right'><a href='/#/merchant-account/account-master' class='btn btn-default btn-sm'><i class='icon-reply'></i> Back List</a> <button onclick='logIn()' class='btn btn-primary btn-sm'><i class='icon-plus'></i> Add</button></span>">-->
      <div v-if="showMasterAccountList" class="card">
        <div class="card-header">
          <div>
            <i class="fa fa-align-justify"></i> List Data 
            <span class="pull-right">
              <a href="/#/merchant-account/account-master" class="btn btn-default btn-sm"><i class="icon-reply"></i> Back List</a>
              <button @click="viewAccount(0,1)" class="btn btn-primary btn-sm"><i class="icon-plus"></i> Add</button>
            </span>
          </div>
        </div>
        <div class="card-block">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Merchant ID</th>
                <th>Account No</th>
                <th>Deposit</th>
                <th>Avail.Balance</th>
                <th>Currency</th>
                <th>Acc.Type</th>
                <th>Acc.Rule</th>
                <th>Trans.Rule</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="data, key in accountCurrencyData['data']">
                <td>{{++key}}</td>
                <td>{{data.merchant_id}}</td>
                <td>{{data.account_no}}</td>
                <td>{{data.deposit}}</td>
                <td>{{data.credit_amount}}</td>
                <td>{{data.currency}}</td>
                <td>{{data.account_type}}</td>
                <td>{{data.account_rule}}</td>
                <td>{{data.transaction_rule}}</td>
                <td>
                  <button type="button" @click="viewAccountDetail(data.account_id)" class="btn btn-success"><i class="icon-eye"></i></button>
                  <button type="button" @click="viewAccount(data.account_id,2)" class="btn btn-primary"><i class="icon-note"></i></button>
                  <!--<button @click="exec">Event Click</button>-->
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
      </div>
      <!--</b-card>-->
    </div><!--/.row-->
  </div>

</template>


<script>
 
	import {fetchAccount,fetchAccountDetail,createAccount,updateAccount} from '../../../../../api/merchantAccount/merchantAccount'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
  import AccountDetailList from './Component/AccountDetailList.vue'
  import ModalAccountForm from './Component/ModalAccountForm.vue'

	export default{
    props:['id'],
		data(){
			return{
        formData:{
            account_type_id:'',
            account_no:'',
            account_code:'',
            transaction_rule_id:'',
            currency_id:'',
            account_type_id:'',
            account_rule_id:'',
            country:'',
            is_default:false,
        },
        swim:'',
        account_master_id:this.id,
        showMasterAccountList:true,
        showAccountList:false,
        tabIndex: 0,
        flag_account:0,
        account_no:'',
        transactionRule:[],
        currency:[],
        accountRule:[],
        accountType:[],
        backUrl:'/merchant-account/account-master',
        accountCurrencyData:[],
        accountData:[],
        account_id:0,
        flash: Flash.state,
        successModal: false,
        modalFormCredit:false,
        showFormCredit:false,
        showAccountCredit:true
      }
    },
    components:{
      ModalAccountForm,
      AccountDetailList
    },
		created(){
      // this.successModal = true
      Flash.setLoading(true)
			this.getData()
		},
    watch: {
      modal_close: function(){
        alert("test")
      }
    },
    methods:{
      eventChild: function(accou) {
        this.viewAccountDetail(accou);
        // alert("I love you")
          // alert("here is event from child")
          // console.log('Event from child component emitted', id)
      },
      eventAccount: function() {
        this.getData();
        // alert("I love you")
          // alert("here is event from child")
          // console.log('Event from child component emitted', id)
      },
      eventParent: function() {
          this.$emit('event_parent', 1);
      },
      onBackList(){
        Flash.setLoading(true)
        this.getData()
        this.showAccountList = false
        this.showMasterAccountList = true
      },
      modal_close(){
        this.modalFormCredit = false
      },
      initCreateCredit(){
        this.showFormCredit = true
      },
      viewAccountDetail(account_id){
        this.account_id = account_id
        this.showAccountList = true
        this.showMasterAccountList = false
        this.tabIndex = 0
        Flash.setLoading(true)
        fetchAccountDetail(account_id).then(response => {
          this.accountData = response['data']
          this.account_no = response['data']['account_info']['account_no']
          Flash.setLoading(false)
        })
        this.modalAccountDetail = true
      },
      viewAccount(account_id){
        this.$refs.child.child_method()
        if(account_id!=0){
          Flash.setLoading(true)
          this.successModal = true
          this.flag_account = 2
          fetchAccountDetail(account_id).then(response => {
            // this.accountData = response['data']
            this.account_no = response['data']['account_info']['account_no']
            this.formData.account_master_id = this.id
            this.formData.account_no = response['data']['account_info']['account_no']
            this.formData.account_code = response['data']['account_info']['account_code']
            this.formData.account_type_id = response['data']['account_info']['account_type_id']
            this.formData.transaction_rule_id = response['data']['account_info']['transaction_rule_id']
            this.formData.currency_id = response['data']['account_info']['currency_id']
            this.formData.account_type_id = response['data']['account_info']['account_type_id']
            this.formData.account_rule_id = response['data']['account_info']['account_rule_id']
            this.formData.country = response['data']['account_info']['country']
            Flash.setLoading(false)
          })
        }else{
          this.flag_account = 1
          this.successModal = true
          this.account_no = ''
          this.formData.account_master_id = this.id
          this.formData.account_no = Math.floor(Math.random() * (99999999 - 1 + 1)) + 1
          this.formData.account_code = Math.floor(Math.random() * (99999999 - 1 + 1)) + 1
          this.formData.account_type_id = null
          this.formData.transaction_rule_id = null
          this.formData.currency_id = null
          this.formData.account_type_id = null
          this.formData.account_rule_id = null
        }
      },
      getData() {
        fetchAccount(this.id).then(response => {
          this.accountCurrencyData = response
          Flash.setLoading(false)
        })
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
              this.getData()
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