<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Send Money Form : '" ok-only size="md" class="modal-primary" v-model="showSendMoneyForm" @ok="showSendMoneyForm = false">
                <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
                <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
                <div class="row">
                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                                    label="Choose Account *"
                                    label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="accountCurrency"
                                            required
                                            v-model="formData.account_id">
                            </b-form-select>
                        </b-form-group>
                    </div>

                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                                    label="Delivery Method *"
                                    label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="deliveryMethod"
                                            required
                                            v-model="formData.delivery_method_id">
                            </b-form-select>
                        </b-form-group>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Send To Account No (Send to account wing or bank account no)">
                            <b-form-input type="text" v-model="formData.send_to_merchant_account_no" placeholder="Send To Account No"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Sending Amount *">
                            <b-form-input type="text" v-model="formData.sending_amount" required placeholder="Sending Amount"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                            label="Receiving Currency *"
                            label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="currency"
                                            required
                                            v-model="formData.receiving_currency">
                            </b-form-select>
                        </b-form-group>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Charge Fee Amount *">
                            <b-form-input type="number" v-model="formData.charge_fee" required placeholder="Charge Fee Amount"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Receiving Date *">
                            <b-form-input type="date" v-model="formData.receiving_date" required placeholder="Receiving Date"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Receiving Contact *">
                            <b-form-input type="text" v-model="formData.receiving_contact" required placeholder="Receiving Contact"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Receiving Name">
                            <b-form-input type="text" v-model="formData.receiving_name" placeholder="Receiving Name"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Remark *">
                            <b-form-input type="text" v-model="formData.remark" required placeholder="Remark"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <b-form-fieldset>
                                    <b-form-checkbox v-model="formData.is_received">Is Received?</b-form-checkbox>
                                </b-form-fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <div slot="modal-footer" class="w-100">
                    <span class="pull-right">
                        <b-button type="reset" @click="showSendMoneyForm = false" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
                        <!--<button v-on:click="emit">Clique me to emit from child component</button>-->
                        <b-button v-if="IsStatusApproved" type="submit" variant="primary">Submit</b-button>
                    </span>
                </div>
            </b-modal>
        </b-form>
    </div>
</template>

<script>
    import {fetchDeliveryMethodList} from '../../../../../../api/setupmaster/deliveryMethod'
    import {createData,editData,updateData} from '../../../../../../api/money/sendMoney'
    import {fetchAccount} from '../../../../../../api/merchantAccount/merchantAccount'
    import {fetchCurrencyList} from '../../../../../../api/setupmaster/currency'

    import Flash from '../../../../../../services/flash'

    export default{
        props:[
            'showAction',
            'account_id',
            'accountCurrencyData',
            'account_master_id',
            'flag'
        ],
        data(){
			return{
                showForm:false,
                showAccountCredit:false,
                showSendMoneyForm:false,
                flash: Flash.state,
                account_send_money_id:0,
                IsStatusApproved:true,
                deliveryMethod:[{text:'Choose Delivery Method', value: null }],
                accountCurrency:[{text:'Choose Account', value: null }],
                currency:[{text:'Choose Currency', value: null }],
                formData:{
                    receiving_currency:null,
                    account_id:this.account_id,
                    delivery_method_id:null,
                    exchange_rate:0,
                    charge_fee:0,
                    sending_amount:'',
                    account_master_id:this.account_master_id,
                    sending_currency:'',
                    receiving_contact:'',
                    receiving_name:'',
                    send_to_merchant_account_no:'',
                    receiving_date:'',
                    is_received:false,
                    remark:'',
                    status:false
                }
            }
        },
        created(){
            this.showAction = this.showAction
            this.getAllDeliveryMethod()
            this.getAccount()
            this.formData.account_id = this.account_id
            this.getAllCurrency()
        },
        methods:{
            // emit: function() {
			//     this.$emit('event_child', 1)
		    // },
            child_method(flag,data){
                this.showSendMoneyForm = true
                if(flag!=1){
                    // this.formData.credit_amount = data.credit_amount
                    this.formData.account_id = data.account_id
                    this.formData.delivery_method_id = data.delivery_method_id
                    this.formData.exchange_rate = data.exchange_rate
                    this.formData.charge_fee = data.charge_fee
                    this.formData.sending_amount = data.sending_amount
                    this.formData.account_master_id = data.account_master_id
                    this.formData.sending_currency = data.sending_currency
                    this.formData.receiving_currency = data.receiving_currency
                    this.formData.receiving_contact = data.receiving_contact
                    this.formData.receiving_name = data.receiving_name
                    this.formData.send_to_merchant_account_no = data.send_to_merchant_account_no
                    this.formData.receiving_date = data.receiving_date
                    if(data.is_received==1){
                        this.formData.is_received = true
                    }else{
                        this.formData.is_received = false
                    }
                    this.formData.remark = data.remark
                    this.formData.status = data.status
                    this.account_send_money_id = data.account_send_money_id
                    if(data.is_received){
                        this.IsStatusApproved = false
                    }else{
                        this.IsStatusApproved = true
                    }
                }else{
                    this.IsStatusApproved = true
                    // this.clearForm()
                }
            },
            getAllCurrency() {
                fetchCurrencyList().then(response => {
                    // this.datatable = response
                    for(let x=0 ; x <= response['data'].length-1 ; x++){
                        let data = {'text':response['data'][x]['code'],'value':response['data'][x]['code']}
                        this.currency.push(data)
                    }
                })
            },
            getAccount() {
                // fetchAccount(this.account_master_id).then(response => {
                //     // this.accountCurrency = response
                //     for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                //         let dataAccount = {'text':response['data'][x]['account_no']+'('+response['data'][x]['currency']+')','value':response['data'][x]['account_id']}
                //         this.accountCurrency.push(dataAccount)
                //     }
                // })
                const AccCurentcy = this.accountCurrencyData.data
                for(let x = 0 ; x <= AccCurentcy.length - 1 ; x++){
                    let dataAccount = {'text':AccCurentcy[x]['account_no']+'('+AccCurentcy[x]['currency']+')','value':AccCurentcy[x]['account_id']}
                    this.accountCurrency.push(dataAccount)
                }
            },
            getAllDeliveryMethod() {
                fetchDeliveryMethodList().then(response => {
                    for(let x = 0 ; x <= response['data'].length-1 ; x++){
                        let data = {'text':response['data'][x]['name'],'value':response['data'][x]['delivery_method_id']}
                        this.deliveryMethod.push(data)
                    }
                })
            },
            clearForm(){
                // this.formData = ''
            },
            // getData(dataAccounCredit) {
            //     console.log(dataAccounCredit)
            //     this.formData.credit_amount = dataAccounCredit['deposit_amount']
            // },
            onSubmit (evt) {
                evt.preventDefault()
                if(this.flag==1){
                    createData(this.formData).then(response => {
                        if(response.success == true){
                            Flash.setSuccess(response.message)
                            this.$emit('event_child', this.account_id)
                            this.successModal = false
                            this.showSendMoneyForm = false
                            this.clearForm()
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                        }
                    }).catch(e => {
                        Flash.setError("Error while trying to create data!")
                        this.showSendMoneyForm = false
                        // this.errors.push(e)
                    })
                }else{
                    updateData(this.formData,this.account_send_money_id).then(response => {
                        if(response.success == true){
                            Flash.setSuccess(response.message)
                            this.$emit('event_child', this.account_id)
                            this.successModal = false
                            this.showSendMoneyForm = false
                            this.clearForm()
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                            this.successModal = false
                            this.showSendMoneyForm = false
                        }
                    }).catch(e => {
                        Flash.setError("Error while trying to update data!")
                        this.showSendMoneyForm = false
                        // this.errors.push(e)
                    })
                }
                
            }
        }  
    }
</script>