Sub CreateInteriorDesignPresentation()
    ' Define PowerPoint Variables
    Dim pptApp As Object
    Dim pptPres As Object
    Dim currentSlide As Object
    Dim titleBox As Object
    Dim contentBox As Object
    Dim shp As Object
    Dim trafficLight As Object
    
    ' Create new Presentation
    Set pptApp = CreateObject("PowerPoint.Application")
    pptApp.Visible = True
    Set pptPres = pptApp.Presentations.Add

    ' --- SETUP THEME (Dark Premium) ---
    ' Analysis: We cannot easily load external templates via VBA without a file path.
    ' Instead, we will format the Master Slide background to Black/Dark Grey.
    With pptPres.SlideMaster.Background.Fill
        .ForeColor.RGB = RGB(15, 23, 42) ' Dark Slate Color
        .Solid
    End With
    
    ' Default Text Styles on Master
     With pptPres.SlideMaster.TextStyles(ppTitleTextStyle).TextFrame.TextRange.Font
        .Name = "Arial"
        .Color.RGB = RGB(212, 175, 55) ' Gold
        .Bold = msoTrue
    End With
    With pptPres.SlideMaster.TextStyles(ppBodyTextStyle).TextFrame.TextRange.Font
        .Name = "Arial"
        .Color.RGB = RGB(220, 220, 220) ' Light Grey
    End With
    
    
    ' ==========================================
    ' SLIDE 1: TITLE
    ' ==========================================
    Set currentSlide = pptPres.Slides.Add(1, 1) ' ppLayoutTitle
    
    ' Main Title
    With currentSlide.Shapes(1).TextFrame.TextRange
        .Text = "Interior Design Viewer"
        .Font.Size = 54
        .Font.Color.RGB = RGB(212, 175, 55) ' Gold
    End With
    
    ' Subtitle
    With currentSlide.Shapes(2).TextFrame.TextRange
        .Text = "A Web-Based Room Visualization & Budgeting Tool" & vbCrLf & vbCrLf & _
                "Presented By:" & vbCrLf & "[Your Name]" & vbCrLf & "[Your ID]"
        .Font.Size = 24
        .Font.Color.RGB = RGB(200, 200, 200)
    End With
    
    
    ' ==========================================
    ' SLIDE 2: ABSTRACT
    ' ==========================================
    Set currentSlide = pptPres.Slides.Add(2, 2) ' ppLayoutText
    currentSlide.Shapes(1).TextFrame.TextRange.Text = "Abstract"
    
    With currentSlide.Shapes(2).TextFrame.TextRange
        .Text = "Overview:" & vbCrLf & _
                "Allows users to visualize wall/floor textures on their own room photos before buying." & vbCrLf & vbCrLf & _
                "Technology Stack:" & vbCrLf & _
                "- Frontend: HTML5, Tailwind CSS, JavaScript (Canvas API)" & vbCrLf & _
                "- Backend: PHP (Routing System)" & vbCrLf & _
                "- Database: MySQL (Relational)"
        .Paragraphs(1).Font.Bold = msoTrue
        .Paragraphs(4).Font.Bold = msoTrue
    End With


    ' ==========================================
    ' SLIDE 3: MODULE LIST
    ' ==========================================
    Set currentSlide = pptPres.Slides.Add(3, 2) ' ppLayoutText
    currentSlide.Shapes(1).TextFrame.TextRange.Text = "Module List"
    
    With currentSlide.Shapes(2).TextFrame.TextRange
        .Text = "1. User Portal (Frontend)" & vbCrLf & _
                vbTab & "- Home & Featured Trends" & vbCrLf & _
                vbTab & "- Material Gallery (Walls, Floors)" & vbCrLf & _
                vbTab & "- Interactive Room Visualizer" & vbCrLf & _
                vbTab & "- Budget Planner" & vbCrLf & vbCrLf & _
                "2. Admin Portal (Backend)" & vbCrLf & _
                vbTab & "- Analytical Dashboard" & vbCrLf & _
                vbTab & "- Inventory Management (CRUD)" & vbCrLf & _
                vbTab & "- Quote Management System"
    End With


    ' ==========================================
    ' SLIDE 4: SUB-MODULE EXPLANATION
    ' ==========================================
    Set currentSlide = pptPres.Slides.Add(4, 2) ' ppLayoutText
    currentSlide.Shapes(1).TextFrame.TextRange.Text = "Key Sub-Modules"
    
    ' Left Column Text
    With currentSlide.Shapes(2).TextFrame.TextRange
        .Text = "The Visualizer Engine" & vbCrLf & _
                "- Image Upload: Handles high-res user photos." & vbCrLf & _
                "- Polygon Tool: Users click to define wall boundaries." & vbCrLf & _
                "- Rendering: Uses HTML Canvas 'Multiply' blend mode for realistic shadows." & vbCrLf & vbCrLf & _
                "Budget Planner" & vbCrLf & _
                "- Formula: Area (sqft) * Material Unit Price." & vbCrLf & _
                "- Real-time database fetching."
    End With


    ' ==========================================
    ' SLIDE 5: OUTPUT - VISUALIZER
    ' ==========================================
    Set currentSlide = pptPres.Slides.Add(5, 12) ' ppLayoutBlank
    
    ' Add Title Manually
    Set titleBox = currentSlide.Shapes.AddTextbox(1, 50, 20, 600, 50) ' msoTextOrientationHorizontal
    With titleBox.TextFrame.TextRange
        .Text = "Output: Room Visualizer"
        .Font.Size = 36
        .Font.Bold = msoTrue
        .Font.Color.RGB = RGB(212, 175, 55)
    End With
    
    ' Add Placeholder box
    Set shp = currentSlide.Shapes.AddShape(1, 100, 100, 500, 300) ' msoShapeRectangle
    With shp
        .Fill.ForeColor.RGB = RGB(50, 50, 50)
        .Line.DashStyle = 4 ' msoLineDash
        .TextFrame.TextRange.Text = "[ INSERT VISUALIZER SCREENSHOT HERE ]"
        .TextFrame.TextRange.Font.Color.RGB = RGB(255, 255, 255)
    End With


    ' ==========================================
    ' SLIDE 6: OUTPUT - MATERIALS
    ' ==========================================
    Set currentSlide = pptPres.Slides.Add(6, 12) ' ppLayoutBlank
    
    ' Add Title
    Set titleBox = currentSlide.Shapes.AddTextbox(1, 50, 20, 600, 50)
    With titleBox.TextFrame.TextRange
        .Text = "Output: Material Gallery"
        .Font.Size = 36
        .Font.Bold = msoTrue
        .Font.Color.RGB = RGB(212, 175, 55)
    End With
    
    ' Add Placeholder box
    Set shp = currentSlide.Shapes.AddShape(1, 100, 100, 500, 300)
    With shp
        .Fill.ForeColor.RGB = RGB(50, 50, 50)
        .Line.DashStyle = 4
        .TextFrame.TextRange.Text = "[ INSERT MATERIAL PAGE SCREENSHOT HERE ]"
        .TextFrame.TextRange.Font.Color.RGB = RGB(255, 255, 255)
    End With


    ' ==========================================
    ' SLIDE 7: DATABASE TABLES
    ' ==========================================
    Set currentSlide = pptPres.Slides.Add(7, 2)
    currentSlide.Shapes(1).TextFrame.TextRange.Text = "Database Schema"
    
    ' Create Table
    Dim oTable As Object
    Set oTable = currentSlide.Shapes.AddTable(7, 2, 50, 120, 600, 300).Table
    
    ' Headers
    oTable.Cell(1, 1).Shape.TextFrame.TextRange.Text = "Table Name"
    oTable.Cell(1, 2).Shape.TextFrame.TextRange.Text = "Description"
    
    ' Row Data
    oTable.Cell(2, 1).Shape.TextFrame.TextRange.Text = "admin_users"
    oTable.Cell(2, 2).Shape.TextFrame.TextRange.Text = "Secure admin login credentials"
    
    oTable.Cell(3, 1).Shape.TextFrame.TextRange.Text = "categories"
    oTable.Cell(3, 2).Shape.TextFrame.TextRange.Text = "Material types (Wall, Floor)"
    
    oTable.Cell(4, 1).Shape.TextFrame.TextRange.Text = "materials"
    oTable.Cell(4, 2).Shape.TextFrame.TextRange.Text = "Inventory (Price, Image, Name)"
    
    oTable.Cell(5, 1).Shape.TextFrame.TextRange.Text = "quotes"
    oTable.Cell(5, 2).Shape.TextFrame.TextRange.Text = "User pricing requests"
    
    oTable.Cell(6, 1).Shape.TextFrame.TextRange.Text = "feedback"
    oTable.Cell(6, 2).Shape.TextFrame.TextRange.Text = "Contact inquiries"
    
    oTable.Cell(7, 1).Shape.TextFrame.TextRange.Text = "reviews"
    oTable.Cell(7, 2).Shape.TextFrame.TextRange.Text = "User ratings"
    
    ' Style Table
    Dim i As Integer, j As Integer
    For i = 1 To 7
        For j = 1 To 2
            oTable.Cell(i, j).Shape.TextFrame.TextRange.Font.Size = 14
            oTable.Cell(i, j).Shape.TextFrame.TextRange.Font.Color.RGB = RGB(0, 0, 0)
            If i = 1 Then
                oTable.Cell(i, j).Shape.Fill.ForeColor.RGB = RGB(212, 175, 55) ' Gold Header
                oTable.Cell(i, j).Shape.TextFrame.TextRange.Font.Bold = msoTrue
            Else
                oTable.Cell(i, j).Shape.Fill.ForeColor.RGB = RGB(240, 240, 240) ' White/Grey Rows
            End If
        Next j
    Next i


    ' ==========================================
    ' SLIDE 8: THANK YOU
    ' ==========================================
    Set currentSlide = pptPres.Slides.Add(8, 1) ' ppLayoutTitle
    
    With currentSlide.Shapes(1).TextFrame.TextRange
        .Text = "Thank You!"
        .Font.Size = 60
        .Font.Color.RGB = RGB(212, 175, 55)
    End With
    
    With currentSlide.Shapes(2).TextFrame.TextRange
        .Text = "Any Questions?"
        .Font.Size = 32
        .Font.Color.RGB = RGB(255, 255, 255)
    End With

    MsgBox "Presentation Created Successfully! You can now edit the slides and insert your images.", vbInformation

End Sub
