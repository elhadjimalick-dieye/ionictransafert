import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class TransactionService {

  constructor(private http: HttpClient) { }
  private  endpoint = 'http://127.0.0.1:8000/api/envoie';
  private  endpoint1 = 'http://127.0.0.1:8000/api/retrait';
  private  endpoint4 = 'http://127.0.0.1:8000/api/frais';
  private trans = "http://localhost:8000/api/transaction";




  envoie(formData){

  const formData1: FormData = new FormData;

  formData1.append('nomE', formData.nomE);
  formData1.append('prenomE', formData.prenomE);
  formData1.append('telE', formData.telE);
  formData1.append('nomEx', formData.nomEx);
  formData1.append('prenomEx', formData.prenomEx);
  formData1.append('telephoneEx', formData.telephoneEx);
  formData1.append('adresseEx', formData.adresseEx);
  formData1.append('montant', formData.montant);
  console.log(formData);
  const heades = new HttpHeaders().set("Authorization", "Bearer " + localStorage.getItem('token'));
  return this.http.post(this.endpoint, formData1 , { headers: heades });
}

frais(Data){
  console.log(Data);
  
  const formData4: FormData = new FormData();
  formData4.append('montant',Data);
  const heades = new HttpHeaders().set("Authorization", "Bearer " + localStorage.getItem('token'));
return this.http.post(this.endpoint4, formData4, { headers: heades });
}
retrait(formData){

  const formData2: FormData = new FormData;

  formData2.append('code', formData.code);
  formData2.append('cni', formData.cni);

  console.log(formData);
  return this.http.post(this.endpoint1, formData2);
}



listeTransaction(data) {
  const headers = new HttpHeaders().set('Authorization', 'Bearer ' + localStorage.getItem('token'));
  return this.http.post<any>(this.trans , data, {headers:headers});
 }
}
