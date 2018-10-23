import axios from 'axios'
import config from '../config'
import Flash from '../../services/flash'
var url = '/api/fileUplaod';
// create
export function serviceUploadFile(data) {
	return axios.post(url,data
	).then((response)=>{
		if(response.data.success==true){
			Flash.setLoading(false)
			return response['data'];
		}else{
			return response['data'];
		}
	})
	.catch((err) => {
		if(err.response.status === 422) {
			return err.response.message
		}
	})
}