<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Attachment Preview</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 15px;
            overflow: hidden;
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f96332 0%, #ff8c42 100%);
            padding: 12px 25px;
            border-radius: 12px;
            color: #ffffff;
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14);
            margin-bottom: 15px;
        }

        .preview-header h4 {
            margin: 0;
            font-weight: bold;
            font-size: 16px;
        }

        .image-card-container {
            flex: 1;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            overflow: auto;
            /* يسمح بالتمرير إذا كانت الصورة ضخمة جداً بدلاً من ضغطها بوهن */
        }

        .image-card-container img {
            max-width: 100%;
            max-height: 85vh;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            /* هذه الخصائص تحافظ على حدة ألوان البكسلات وتمنع الضبابية أثناء التمدد */
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }

        .btn-now-ui {
            background-color: #ffffff;
            color: #f96332;
            border-radius: 30px;
            padding: 6px 20px;
            font-weight: bold;
            font-size: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn-now-ui:hover {
            background-color: #f1f1f1;
            color: #e55320;
        }
    </style>
</head>

<body>

    <div class="preview-header">
        <h4><i class="now-ui-icons design_image mr-2"></i> Attachment Preview</h4>
        <a href="{{ asset('storage/' . request('path')) }}" download class="btn btn-now-ui shadow-sm">
            <i class="now-ui-icons arrows-1_cloud-download-93 mr-1"></i> Download Image
        </a>
    </div>

    <div class="image-card-container">
        <img src="{{ asset('storage/' . request('path')) }}" alt="HD Attachment Preview">
    </div>

</body>

</html>