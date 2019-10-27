import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { JwtHelperService } from '@auth0/angular-jwt';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  [x: string]: any;

  jwt: string;
  username: string;
  roles: Array<string>;


  // tslint:disable-next-line: variable-name
  private _loginUrl = 'http://localhost:8000/api/login_check';

  // tslint:disable-next-line: variable-name
  constructor(private http: HttpClient, private _router: Router) { }

  loginUser(data) {
    return this.http.post<any>(this._loginUrl, data);
  }

  loggedIn() {
    // tslint:disable-next-line: whitespace
    return !!localStorage.getItem('token');
  }
  // methode de deconnexion

  logoutUser() {
    localStorage.removeItem('token');
    this._router.navigate(['/login']);
    this.initParams();
  }

  initParams() {
    this.jwt = undefined;
    this.username = undefined;
    this.roles = undefined;
  }

  getToken() {
    return localStorage.getItem('token');
  }

  saveToken(jwt: string) {
    localStorage.setItem('token', jwt);
    this.jwt = jwt;
    this.parseJWT();
  }

  parseJWT() {
    const jwtHelper = new JwtHelperService();
    const objJWT = jwtHelper.decodeToken(this.jwt);
    this.username = objJWT.obj;
    this.roles = objJWT.roles;
    console.log(objJWT);
  }

  isAdmin() {
    return this.roles.indexOf('ROLE_SUPER_ADMIN') >= 0;
  }

  isCaissier() {
    return this.roles.indexOf('ROLE_CAISSIER') >= 0;
  }

  isPartenaire() {
    return this.roles.indexOf('ROLE_ADMIN_PARTENAIRE') >= 0;
  }

  isUser() {
    return this.roles.indexOf('ROLE_USER') >= 0;
  }
  // vous pouvez vous authentifier soit user soit admin cela depant de votre status
  isAuthenticated() {
    return this.roles && (this.isAdmin() || this.isCaissier() || this.isUser() || this.isPartenaire());
  }

  // cette methode va nous permettre à chaque fois qu'on actualise la page
  // qu'on ne puisse pas s'authentifier à nouveau
  loadToken() {
    this.jwt = localStorage.getItem('token');
    this.parseJWT();
  }
  
}
