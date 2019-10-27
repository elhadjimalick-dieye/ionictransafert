import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RetraitPage } from './retrait.page';

describe('RetraitPage', () => {
  let component: RetraitPage;
  let fixture: ComponentFixture<RetraitPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RetraitPage ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RetraitPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
