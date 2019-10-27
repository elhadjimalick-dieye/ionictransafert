import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class RegisterService {

  constructor(private http: HttpClient) { }
  private  endpoint1 = 'http://127.0.0.1:8000/api/register';


  register(formData){

  const formData1: FormData = new FormData;

  formData1.append('nom', formData.nom);
  formData1.append('prenom', formData.prenom);
  formData1.append('username', formData.username);
  formData1.append('plainPassword', formData.plainPassword);
  formData1.append('image', formData.image);
  
  console.log(formData);
  return this.http.post(this.endpoint1, formData1);
}
}
