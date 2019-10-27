import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TransactionlistePage } from './transactionliste.page';

describe('TransactionlistePage', () => {
  let component: TransactionlistePage;
  let fixture: ComponentFixture<TransactionlistePage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TransactionlistePage ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TransactionlistePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
