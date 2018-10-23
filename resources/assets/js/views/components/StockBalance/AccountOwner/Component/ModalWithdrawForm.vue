<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Withdraw Form : '" ok-only size="md" class="modal-primary" v-model="showCreditForm" @ok="showCreditForm = false">
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
                        <b-form-fieldset label="Request Amount *">
                            <b-form-input type="text" v-model="formData.request_amount" required placeholder="Request Amount"></b-form-input>
                        </b-form-fieldset>
                    </div>

                    <div class="col-sm-12">
                        <b-form-fieldset label="Request Date *">
                            <b-form-input type="date" v-model="formData.requested_date" required placeholder="Request Date"></b-form-input>
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
                    <div class="col-sm-12">
                        <b-form-fieldset>
                            <b-form-checkbox v-model="formData.is_approved"> Is Approved?</b-form-checkbox>
                        </b-form-fieldset>
                    </div>
                </div>
                <div slot="modal-footer" class="w-100">
                    <span class="pull-right">
                        <b-button type="reset" @click="showCreditForm = false" variant="default"><i class="fa fa-ban"></i> Cancel</b-button>
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
    import {createData,editData,updateData} from '../../../../../../api/merchantAccount/accountWithdrawal'
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
                showCreditForm:false,
                flash: Flash.state,
                deliveryMethod:[{text:'Choose Delivery Method', value: null }],
                accountCurrency:[{text:'Choose Account', value: null }],
                loanProduct:[{text:'Choose Product', value: null }],
                termDay:[{text:'Choose Term', value: null }],
                paymentCycle:[{text:'Choose Payment Cycle', value: null }],
                interestMethod:[{text:'Choose Interest Method', value: null }],

                formData:{
                    account_id:this.account_id,
                    request_amount:'',
                    requested_date:'',
                    is_approved:true,
                    status:true,
                    remark:''
                },
                account_credit_id:0,
                IsStatusApproved:true
            }
        },
        created(){
            this.showAction = this.showAction
            this.getAllDeliveryMethod()
            this.getAccount()
        },
        methods:{
            // emit: function() {
			//     this.$emit('event_child', 1)
		    // },
            getAccount() {
                fetchAccount(this.account_master_id).then(response => {
                    for(let x = 0 ; x <= response['data'].length - 1 ; x++){
                        let data = {'text':response['data'][x]['account_no'],'value':response['data'][x]['account_id']}
                        this.accountCurrency.push(data)
                    }
                })
            },
            child_method(flag,data){
                this.showCreditForm = true
                this.formData.account_id = data.account_id
                this.formData.request_amount = data.request_amount
                this.formData.requested_date = data.requested_date
                this.formData.is_approved = data.is_approved
                this.formData.status = data.status
                this.formData.remark = data.remark
                // if(flag!=1){
                //     if(data.status==1){
                //         this.IsStatusApproved = false
                //     }
                // }
            },
            getAllDeliveryMethod() {
                fetchDeliveryMethodList().then(response => {
                // this.datatable = response
                for(let x=0 ; x <= response['data'].length-1 ; x++){
                    let data = {'text':response['data'][x]['delivery_method'],'value':response['data'][x]['delivery_method_id']}
                    this.deliveryMethod.push(data)
                }
                })
            },
            clearForm(){
                this.formData.account_id = this.account_id
                this.formData.request_amount = ''
                this.formData.requested_date = ''
                this.formData.is_approved = true
                this.formData.status = true
                this.formData.remark = ''
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
                            this.showCreditForm = false
                            this.clearForm()
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                        }
                    })
                }else{
                    updateData(this.formData,this.account_credit_id).then(response => {
                            if(response.success == true){
                            Flash.setSuccess(response.message)
                            // this.getData()
                        }else{
                            Flash.setError(response.message)
                        }
                    })
                }
                
            }
        }  
    }
</script>