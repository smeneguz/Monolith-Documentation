SHELL := /bin/bash
pdflatex = pdflatex -interaction=nonstopmode
file = presentazione_RA
filenewname = PresentazioneRA.pdf

doc:
	$(pdflatex) $(file).tex
	$(pdflatex) $(file).tex
	mv $(file).pdf $(filenewname)

clean:
	rm *.{aux,idx,ind,lof,lot,out,toc,acn,acr,alg,glg,glo,gls,ist,log,pdf} 2&> /dev/null || true
