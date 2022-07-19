import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'burnfest2022';

  nom : String = ""
  prenom : String = ""
  suze : String = ""
  picon : String = ""
  camping : String = ""
  dimitri : String = ""
  saucisses : String = ""
  inscris = false

  constructor(private httpClient : HttpClient, private router : Router){}

  ngOnInit(): void {
    this.router.navigate(['35ansjulburn']).then();
  }

  setSuze(data: String) {
    this.suze = data
  }

  setPicon(data: String) {
    this.picon = data
  }

  setCamping(data : String) {
    this.camping = data
  }

  setDimitri(data : String) {
    this.dimitri = data
  }

  setSaucisses(data : String) {
    this.saucisses = data
  }

  inscription() {
    this.httpClient.get("https://www.burnprojects.be/35ansjulburn/insertsomebody?nom=" + this.nom + "&prenom=" + this.prenom + "&suze=" + this.suze + "&picon=" + this.picon + "&camping=" + this.camping + "&dimitri=" + this.dimitri + "&saucisses=" + this.saucisses).subscribe(
    (response) => {
      if (response) {
        this.inscris = true
      } else {
        alert("Problème d'inscription, t'es sur que t'as pas écris de la merde ?")
      }
    }
    )
  }
}
