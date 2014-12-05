## Alma Link Resolver Problem Reporting Form
=================================

This is a simple form that lets users report problems they encounter while using the CSUSM Alma link resolver. The form captures the OpenURL data and sends that with the form report to our Alma SysAdmins.

To see how the form works, please go to http://primo-pmtna01.hosted.exlibrisgroup.com/primo_library/libweb/action/search.do?mode=Basic&vid=CALS_USM&tab=cals_usm_pci and perform a search.

We are piloting this form using a General Electronic Service [GES] in Alma. in the GES configuration we use the following as the URL Template.

https://biblio.csusm.edu/primo_fx/report.php?rft.issn={rft.issn}&rft.eissn={rft.eissn}&rft.isbn={rft.isbn}&rft.au={rft.au}&rft.ausuffix={rft.ausuffix}&rft.aucorp={rft.aucorp}&rft.volume={rft.volume}&rft.month={rft.month}&rft.genre={rft.genre}&rft.auinit={rft.auinit}&rft.pub={rft.pub}&rft.issue={rft.issue}&rft.place={rft.place}&rft.title={rft.title}&rft.stitle={rft.stitle}&rft.btitle={rft.btitle}&rft.jtitle={rft.jtitle}&rft.aufirst={rft.aufirst}&linktype=openurl&rft.atitle={rft.atitle}&rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Aarticle&rft.auinit1={rft.auinit1}&rft.date={rft.year}&rft.year={rft.year}&url_ver=Z39.88-2004&rft.aulast={rft.aulast}&rft.spage={rft.spage}&rft.epage={rft.epage}&rfr_id=info:doi/{rfr_id}

Rft.year and pages may need adjustment.

In our workflow, we send the email to Redmine which automatically generates a ticket. Redmine than notifies our Alma SysAdmins. We are track updates and issue resolution via the Redmine ticket.
