import { Component, OnInit } from '@angular/core';
import { TransactionService } from '../services/transaction.service';
import { HttpClient } from 'selenium-webdriver/http';
import { FormGroup, FormControl, Validators } from '@angular/forms';

@Component({
  selector: 'app-transaction',
  templateUrl: './transaction.page.html',
  styleUrls: ['./transaction.page.scss'],
})
export class TransactionPage implements OnInit {
envoieData: any={};
retraitData :any={};
values= {}
Data:any= {};



  constructor(private transaction: TransactionService) { }

  ngOnInit() {
  }

  envoie(){
    this.transaction.envoie(this.envoieData)
    .subscribe(
      data => {
        window.confirm('Envoie reussie');
        console.log(data);
      },
      err=> {
        window.confirm('Envoie echoué');
      }
    );
  }


  retrait(){
    this.transaction.retrait(this.retraitData)
    .subscribe(
      data => {
        window.confirm('retrait reussi');
        console.log(data);
      },
      err=> {
        window.confirm('retrait echoué');
      }
    );
  }

  onKey(){
    console.log(this.envoieData.montant);
    
    this.transaction.frais(this.envoieData.montant)
      .subscribe(
        res => {
          //Swal.fire('retrait effectué') 
          console.log(res);
          this.values = res;
          
         },
         err => { 
          //Swal.fire('retrait effectué') 
          console.log(err);
        }
         
        );

  }
 
  
}
