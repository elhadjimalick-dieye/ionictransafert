import { Component, OnInit } from '@angular/core';
import { TransactionService } from '../services/transaction.service';


@Component({
  selector: 'app-transactionliste',
  templateUrl: './transactionliste.page.html',
  styleUrls: ['./transactionliste.page.scss'],
})
export class TransactionlistePage implements OnInit {
tableau =[];


  constructor( private transaction :TransactionService) { }
  listeTRa(data) {
    console.log(data);
    
    this.transaction.listeTransaction(data)
    .subscribe(
      res => {
        console.log(res);
        
        this.tableau = res
      },
      err => console.log(err)
    );
  }
  ngOnInit() {
  }

  message(){
    "cette intervale ne dispose de transaction"
  }
}
