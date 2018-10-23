<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Send Money sForm : '" ok-only size="md" class="modal-primary" v-model="showSendMoneyForm" @ok="showSendMoneyForm = false">
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
                        <b-form-fieldset label="Sending Amount *">
                            <b-form-input type="text" v-model="formData.sending_amount" required placeholder="Sending Amount"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Sending Date *">
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
                        <b-form-fieldset>
                            <b-form-checkbox v-model="formData.status"> Status</b-form-checkbox>
                        </b-form-fieldset>
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

    import Flash from '../../../../../../services/flash'

    export default{
        props:[
            'showAction',
            'account_id',
            'account_master_id',
            'flag'
        ],
        data(){
			return{
                showForm:false,
                showAccountCredit:false,
                showSendMoneyForm:false,
                flash: Flash.state,
                formData:{
                    account_id:null,
                    delivery_method_id:null,
                    exchange_rate:'0',
                    sending_amount:'',
                    account_master_id:this.account_master_id,
                    sending_currency:'',
                    receiving_currency:'',
                    receiving_amount:'',
                    receiving_contact:'',
                    receiving_name:'',
                    send_to_merchant_account_no:'',
                    receiving_date:'',
                    is_received:false,
                    status:'',
                    remark:'',
                    status:true
                },
                account_credit_id:0,
                IsStatusApproved:true,
                deliveryMethod:[{text:'Choose Delivery Method', value: null }],
                accountCurrency:[{text:'Choose Account', value: null }]
            }
        },
        created(){
            this.showAction = this.showAction
            this.getAllDeliveryMethod()
            this.getAccount()
            this.formData.account_id = this.account_id
        },
        methods:{
            // emit: function() {
			//     this.$emit('event_child', 1)
		    // },
            child_method(flag,data){
                this.showSendMoneyForm = true
                this.formData.credit_amount = data.credit_amount
                this.account_credit_id = data.account_credit_id
                if(flag!=1){
                    if(data.status==1){
                        this.IsStatusApproved = false
                    }
                }
            },
            getAccount() {
                fetchAccount(this.account_master_id).then(response => {
                    // this.accountCurrency = response
                    for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                        let dataAccount = {'text':response['data'][x]['account_no'],'value':response['data'][x]['account_id']}
                        this.accountCurrency.push(dataAccount)
                    }
                })
            },
            getAllDeliveryMethod() {
                fetchDeliveryMethodList().then(response => {
                    // this.datatable = response
                    for(let x = 0 ; x <= response['data'].length-1 ; x++){
                        let data = {'text':response['data'][x]['delivery_method'],'value':response['data'][x]['delivery_method_id']}
                        this.deliveryMethod.push(data)
                    }
                })
            },
            clearForm(){
                this.formData.credit_amount= ''
            },
            // getData(dataAccounCredit) {
            //     console.log(dataAccounCredit)
            //     this.formData.credit_amount = dataAccounCredit['deposit_amount']
            // },
            onSubmit (evt) {
                evt.preventDefault();
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
                        Flash.setError("Error while trying to created data!")
                        this.showSendMoneyForm = false
                        // this.errors.push(e)
                    })
                }else{
                    updateData(this.formData,this.account_credit_id).then(response => {
                            if(response.success == true){
                            Flash.setSuccess(response.message)
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
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