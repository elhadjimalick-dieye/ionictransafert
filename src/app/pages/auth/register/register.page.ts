import { Component, OnInit } from '@angular/core';
import { RegisterService } from 'src/app/services/register.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage implements OnInit {
  registerData ={};


  constructor(private registerservice: RegisterService) { }

  ngOnInit() {
  }
  register(){
    this.registerservice.register(this.registerData)
    .subscribe(
      data => {
        window.confirm('ajout utilisateur reussi');
        console.log(data);
      },
      err=> {
        window.confirm('ajout utilisateur echoué');
      }
    );
  }
}
