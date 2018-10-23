<template>
    <div>
        <b-form @submit="onSubmit" hide-footer>
            <b-modal centered :title="'Account Credit Form : '" ok-only size="md" class="modal-primary" v-model="showCreditForm" @ok="showCreditForm = false">
                <div class="row">
                    <div class="col-sm-12">
                        <b-form-fieldset label="Credit Amount *">
                        <b-form-input type="text" v-model="formData.credit_amount" required placeholder="Credit Amount"></b-form-input>
                        </b-form-fieldset>
                    </div>
                    <div class="col-sm-12">
                        <b-form-fieldset>
                            <b-form-checkbox v-model="formData.is_active"> Is Active?</b-form-checkbox>
                        </b-form-fieldset>

                        <b-form-fieldset>
                            <b-form-checkbox v-model="formData.status"> Status</b-form-checkbox>
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
    import {createData,editData,updateData} from '../../../../../../api/merchantAccount/accountCredit'
    import Flash from '../../../../../../services/flash'

    export default{
        props:[
            'showAction',
            'account_id',
            'flag'
        ],
        data(){
			return{
                showForm:false,
                showAccountCredit:false,
                showCreditForm:false,
                flash: Flash.state,
                formData:{
                    account_id:this.account_id,
                    credit_amount:'',
                    status:true,
                    is_active:true
                },
                account_credit_id:0,
                IsStatusApproved:true
            }
        },
        created(){
            this.showAction = this.showAction
        },
        methods:{
            // emit: function() {
			//     this.$emit('event_child', 1)
		    // },
            child_method(flag,data){
                this.showCreditForm = true
                this.formData.credit_amount = data.credit_amount
                this.account_credit_id = data.account_credit_id
                if(flag!=1){
                    if(data.status==1){
                        this.IsStatusApproved = false
                    }
                }
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