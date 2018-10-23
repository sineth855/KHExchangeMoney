<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Transfer Form : '" ok-only size="md" class="modal-primary" v-model="showTransferForm" @ok="showTransferForm = false">
                <div class="row">
                    <div class="col-sm-12">
                        <b-form-fieldset label="Transfer No">
                            <b-form-input type="text" disabled v-model="formData.transfer_no" required placeholder="Transfer No"></b-form-input>
                        </b-form-fieldset>
                    </div>

                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                                    label="Tranfer From Account *"
                                    label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="accountCurrency"
                                            required
                                            v-model="formData.transfer_from_account_no">
                            </b-form-select>
                        </b-form-group>
                    </div>

                    <div class="col-sm-12">
                        <b-form-fieldset label="Transfer To Account No*">
                            <b-form-input type="text" v-model="formData.transfer_to_account_no" required placeholder="Transfer To Account"></b-form-input>
                        </b-form-fieldset>
                    </div>

                    <div class="col-sm-12">
                        <b-form-group id="exampleInputGroup3"
                                    label="Delivery Method *"
                                    label-for="exampleInput3">
                            <b-form-select id="exampleInput3"
                                            :options="deliveryMethod"
                                            required
                                            v-model="formData.delivery_method">
                            </b-form-select>
                        </b-form-group>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset label="Transfer Amount *">
                            <b-form-input type="number" v-model="formData.transfer_amount" required placeholder="Transfer Amount"></b-form-input>
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
                        <b-button type="reset" @click="showTransferForm = false" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
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
    import {createData,editData,updateData} from '../../../../../../api/money/transferMoney'
    import {fetchAccount} from '../../../../../../api/merchantAccount/merchantAccount'
    import Flash from '../../../../../../services/flash'

    export default{
        props:[
            'showAction',
            'account_id',
            'account_master_id',
        ],
        data(){
			return{
                showForm:false,
                showAccountCredit:false,
                showTransferForm:false,
                flash: Flash.state,
                deliveryMethod:[{text:'Choose Delivery Method', value: null }],
                accountCurrency:[{text:'Choose Account', value: null }],
                formData:{
                    transfer_no:'',
                    transfer_from_account_no:this.account_id,
                    transfer_to_account_no:'',
                    delivery_method:null,
                    transfer_amount:'',
                    remark:'',
                    account_master_id:this.account_master_id,
                    status:true
                },
                flag:0,
                account_credit_id:0,
                IsStatusApproved:true
            }
        },
        created(){
            this.showAction = this.showAction
            this.formData.transfer_from_account_no = this.account_id
            this.getAllDeliveryMethod()
            this.getAccount()
        },
        methods:{
            getAccount() {
                fetchAccount(this.account_master_id).then(response => {
                    for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                        let dataAccount = {'text':response['data'][x]['account_no'],'value':response['data'][x]['account_id']}
                        this.accountCurrency.push(dataAccount)
                    }
                })
            },
            // emit: function() {
            //     this.$emit('event_child', 1)
            // },
            child_method(flag,data){
                this.flag = flag
                this.formData.transfer_no = 'TR-'+Math.floor(Math.random() * (9999999999 - 1 + 1))
                this.showTransferForm = true
                this.formData.credit_amount = data.credit_amount
                this.account_credit_id = data.account_credit_id
                if(flag!=1){
                    if(data.status==1){
                        this.IsStatusApproved = false
                    }
                }
            },
            getAllDeliveryMethod() {
                fetchDeliveryMethodList().then(response => {
                // this.datatable = response
                for(let x=0 ; x <= response['data'].length-1 ; x++){
                    let data = {'text':response['data'][x]['delivery_method'],'value':response['data'][x]['delivery_method']}
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
                evt.preventDefault()
                if(this.flag==1){
                    createData(this.formData).then(response => {
                        if(response.success == true){
                            Flash.setSuccess(response.message)
                            this.$emit('event_child', this.account_id)
                            this.successModal = false
                            this.showTransferForm = false
                            this.clearForm()
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                            this.showTransferForm = false
                        }
                    }).catch(e => {
                        Flash.setError("Error while trying to created data!")
                        this.showTransferForm = false
                        // this.errors.push(e)
                    })
                }else{
                    updateData(this.formData,this.account_credit_id).then(response => {
                        if(response.success == true){
                            Flash.setSuccess(response.message)
                            this.showTransferForm = false
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                            this.showTransferForm = false
                        }
                    }).catch(e => {
                        Flash.setError("Error while trying to update data!")
                        this.showTransferForm = false
                        // this.errors.push(e)
                    })
                }
                
            }
        }  
    }
</script>